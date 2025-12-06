<?php

namespace App\Contremarque\Command;

use App\Contremarque\Entity\CarpetOrder\CarpetOrder;
use App\Contremarque\Entity\CarpetOrder\CarpetOrderDetail;
use App\Contremarque\Repository\CarpetOrderRepository;
use App\Contremarque\Repository\ContremarqueRepository;
use App\Contremarque\Repository\QuoteRepository;
use App\Contremarque\Service\QuoteCloner\QuoteCloner;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:carpet-order-fixtures',
    description: 'Create carpet order fixtures'
)]
class CreateCarpetOrderFixturesCommand extends Command
{
    private const FIXTURE_COUNT = 50;
    private const BATCH_SIZE = 20;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CarpetOrderRepository  $orderRepository,
        private readonly QuoteRepository        $quoteRepository,
        private readonly ContremarqueRepository $contremarqueRepository,
        private readonly QuoteCloner            $quoteCloner
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        foreach ($this->orderRepository->findAll() as $order) {
            if (null !== ($originalQuote = $order->getOriginalQuote())) {
                $this->entityManager->remove($originalQuote);
            }
            if (null !== ($clonedQuote = $order->getClonedQuote())) {
                $this->entityManager->remove($clonedQuote);
            }

            $this->entityManager->remove($order);
        }
        $this->entityManager->flush();

        $quotes = $this->quoteRepository->findAll();
        $contremarques = $this->contremarqueRepository->findAll();

        if (!$quotes || !$contremarques) {
            $io->warning('Quotes or contremarques missing.');
            return Command::SUCCESS;
        }

        $io->progressStart(self::FIXTURE_COUNT);

        for ($i = 0; $i < self::FIXTURE_COUNT; $i++) {
            $order = new CarpetOrder();
            $order->setCreatedAt(new DateTimeImmutable());
            $order->setUpdatedAt(new DateTimeImmutable());

            $originalQuote = $quotes[array_rand($quotes)];
            $clonedQuote  = $this->quoteCloner->cloneQuoteForOrder($originalQuote);

            $order->setReference($clonedQuote->getReference());
            $order->setOriginalQuote($originalQuote);
            $order->setClonedQuote($clonedQuote);
            $order->setContremarque($contremarques[array_rand($contremarques)]);

            $this->entityManager->persist($order);

            // Create CarpetOrderDetail entries for the cloned quote details
            foreach ($clonedQuote->getQuoteDetails() as $quoteDetail) {
                $detail = new CarpetOrderDetail();
                $detail->setCarpetOrder($order);
                $detail->setQuoteDetailId($quoteDetail);
                $detail->setCreatedAt(new \DateTime());
                $detail->setUpdatedAt(new \DateTime());
                $this->entityManager->persist($detail);
            }

            // Flush each iteration to ensure unique references
            $this->entityManager->flush();

            $io->progressAdvance();
        }

        $io->progressFinish();
        $io->success(sprintf('Created %d carpet orders.', self::FIXTURE_COUNT));

        return Command::SUCCESS;
    }
}
