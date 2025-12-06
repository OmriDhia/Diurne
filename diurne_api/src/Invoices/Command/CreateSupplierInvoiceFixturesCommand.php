<?php

namespace App\Invoices\Command;

use App\Invoices\Entity\SupplierInvoice;
use App\Invoices\Repository\SupplierInvoiceRepository;
use App\Setting\Repository\CurrencyRepository;
use App\User\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:supplier-invoice-fixtures',
    description: 'Create supplier invoice fixtures'
)]
class CreateSupplierInvoiceFixturesCommand extends Command
{
    private const FIXTURE_COUNT = 50;

    public function __construct(
        private readonly EntityManagerInterface    $entityManager,
        private readonly SupplierInvoiceRepository $invoiceRepository,
        private readonly CurrencyRepository        $currencyRepository,
        private readonly UserRepository            $userRepository
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        foreach ($this->invoiceRepository->findAll() as $invoice) {
            $this->entityManager->remove($invoice);
        }
        $this->entityManager->flush();

        $currencies = $this->currencyRepository->findAll();
        if (!$currencies) {
            $io->warning('No currencies found.');
            return Command::SUCCESS;
        }

        $author = $this->userRepository->findByEmail('super-user@yopmail.com');
        if (!$author) {
            $io->warning('No user found to assign as author.');
            return Command::SUCCESS;
        }

        $io->progressStart(self::FIXTURE_COUNT);
        for ($i = 0; $i < self::FIXTURE_COUNT; $i++) {
            $invoice = new SupplierInvoice();
            $invoice->setInvoiceNumber($this->invoiceRepository->getNextInvoiceNumber());
            $invoice->setInvoiceDate(new DateTimeImmutable());
            // Try to set manufacturer id 2 if exists, else keep supplier string in description
            // We'll attempt to fetch manufacturer via repository if available from container
            // but for fixtures, to avoid adding repository here, set supplier string and let migration handle manufacturer set
            $invoice->setPackingList('PL-' . ($i + 1));
            // Keep a simple supplier name in the description for backward compatibility
            // (manufacturer mapping will be applied by migration script)
            // NOTE: previous code set supplier; after entity change we store manufacturer relation instead. We'll leave description with legacy supplier name.
            $invoice->setDescription(($invoice->getDescription() ?? '') . ' Legacy supplier: Supplier ' . ($i + 1));
            $invoice->setAirWay('AW-' . ($i + 1));
            $invoice->setFretTotal('50');
            $invoice->setCurrency($currencies[array_rand($currencies)]);
            $invoice->setAmountOther('0');
            $invoice->setWeight('0');
            //$invoice->setDescription('Sample description');
            $invoice->setAuthor($author);
            $this->entityManager->persist($invoice);
            // Flush after each persist so that getNextInvoiceNumber() returns an
            // incremented value on the next iteration. This avoids duplicating
            // invoice numbers when using batch inserts.
            $this->entityManager->flush();

            $io->progressAdvance();
        }

        $io->progressFinish();
        $io->success(sprintf('Created %d supplier invoices.', self::FIXTURE_COUNT));

        return Command::SUCCESS;
    }
}
