<?php

namespace App\Invoices\Command;

use App\Invoices\Entity\CustomerInvoice;
use App\Invoices\Repository\CustomerInvoiceRepository;
use App\Contact\Repository\CustomerRepository;
use App\Contremarque\Repository\CarpetOrderRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:customer-invoice-fixtures',
    description: 'Create customer invoice fixtures'
)]
class CreateCustomerInvoiceFixturesCommand extends Command
{
    private const FIXTURE_COUNT = 50;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CustomerInvoiceRepository $invoiceRepository,
        private readonly CustomerRepository $customerRepository,
        private readonly CarpetOrderRepository $orderRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        foreach ($this->invoiceRepository->findAll() as $invoice) {
            $this->entityManager->remove($invoice);
        }
        $this->entityManager->flush();

        $customers = $this->customerRepository->findAll();
        $orders = $this->orderRepository->findAll();

        if (!$customers) {
            $io->warning('No customers found.');
            return Command::SUCCESS;
        }

        $io->progressStart(self::FIXTURE_COUNT);
        for ($i = 0; $i < self::FIXTURE_COUNT; $i++) {
            $invoice = new CustomerInvoice();
            $invoice->setInvoiceNumber($this->invoiceRepository->getNextInvoiceNumber());
            $invoice->setInvoiceDate(new DateTimeImmutable());
            $invoice->setInvoiceType(1);
            $invoice->setShippingCostsHt('100');
            $invoice->setBilled('100');
            $invoice->setCreatedAt(new DateTimeImmutable());
            $invoice->setCustomer($customers[array_rand($customers)]);
            if ($orders) {
                $invoice->setCarpetOrder($orders[array_rand($orders)]);
            }
            $this->entityManager->persist($invoice);
            // Flush after each persist so that getNextInvoiceNumber() returns an
            // incremented value on the next iteration. This avoids duplicating
            // invoice numbers when using batch inserts.
            $this->entityManager->flush();

            $io->progressAdvance();
        }

        $io->progressFinish();
        $io->success(sprintf('Created %d customer invoices.', self::FIXTURE_COUNT));

        return Command::SUCCESS;
    }
}
