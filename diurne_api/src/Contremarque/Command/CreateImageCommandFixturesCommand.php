<?php

namespace App\Contremarque\Command;

use App\Contremarque\Entity\ImageCommand\ImageCommand;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\ImageCommandRepository;
use App\Workshop\Repository\WorkshopOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:image-command-fixtures',
    description: 'Create  image command fixtures '
)]
class CreateImageCommandFixturesCommand extends Command
{
    private const FIXTURE_COUNT = 100;
    private const BATCH_SIZE = 20;
    private const TARGET_STATUS = 6;

    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly ImageCommandRepository      $imageCommandRepository,
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private readonly WorkshopOrderRepository     $workshopOrderRepository
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Set unlimited memory and execution time
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');

        // Clear existing fixtures
        $this->clearExistingImageCommands($io);

        // Create new fixtures
        $this->createImageCommandFixtures($io);

        return Command::SUCCESS;
    }

    private function clearExistingImageCommands(SymfonyStyle $io): void
    {
        $imageCommands = $this->imageCommandRepository->findAll();

        if (!empty($imageCommands)) {
            $io->note(sprintf('Removing %d existing ImageCommand entities...', count($imageCommands)));

            foreach ($imageCommands as $index => $imageCommand) {
                $imageCommand->setCarpetDesignOrder(null);

                $orders = $this->workshopOrderRepository->findBy(['imageCommand' => $imageCommand]);
                foreach ($orders as $order) {
                    $order->setImageCommand(null);
                }

                $this->entityManager->remove($imageCommand);

                if (0 === $index % self::BATCH_SIZE) {
                    $this->entityManager->flush();
                }
            }

            $this->entityManager->flush();
            $io->success('All existing ImageCommand entities removed.');
        }
    }

    private function createImageCommandFixtures(SymfonyStyle $io): void
    {
        // Get CarpetDesignOrders with status 6 and at least one image
        $eligibleOrders = $this->carpetDesignOrderRepository
            ->createQueryBuilderForFixture('o')
            ->where('o.status = :status')
            ->andWhere('SIZE(o.images) > 0')
            ->setParameter('status', self::TARGET_STATUS)
            ->getQuery()
            ->getResult();

        if (empty($eligibleOrders)) {
            $io->error('No eligible CarpetDesignOrders found (status 6 with images)');
            return;
        }

        $io->note(sprintf(
            'Creating up to %d ImageCommand fixtures from %d eligible CarpetDesignOrders...',
            self::FIXTURE_COUNT,
            count($eligibleOrders)
        ));

        $createdCount = 0;
        $io->progressStart(min(self::FIXTURE_COUNT, count($eligibleOrders)));

        foreach ($eligibleOrders as $index => $order) {
            if ($createdCount >= self::FIXTURE_COUNT) {
                break;
            }

            try {
                $this->createImageCommandForOrder($order, $index);
                $createdCount++;

                if (0 === $createdCount % self::BATCH_SIZE) {
                    $this->entityManager->flush();
                }
                $io->progressAdvance();
            } catch (\Exception $e) {
                $io->warning(sprintf(
                    'Failed to create ImageCommand for order %d: %s',
                    $order->getId(),
                    $e->getMessage()
                ));
            }
        }

        $this->entityManager->flush();
        $io->progressFinish();

        $io->success(sprintf(
            'Created %d ImageCommand fixtures for orders with status %d and images',
            $createdCount,
            self::TARGET_STATUS
        ));

        if ($createdCount < self::FIXTURE_COUNT) {
            $io->warning(sprintf(
                'Only created %d fixtures (requested %d) due to insufficient eligible orders',
                $createdCount,
                self::FIXTURE_COUNT
            ));
        }
    }

    private function createImageCommandForOrder(object $order, int $index): void
    {
        $imageCommand = new ImageCommand();

        $imageCommand->setCommandNumber(sprintf('CMD-ORD-%04d', $index + 1));
        $imageCommand->setCommercialComment($this->generateComment('commercial', $index));
        $imageCommand->setAdvComment($this->generateComment('adv', $index));
        $imageCommand->setStudioComment($this->generateComment('studio', $index));
        $imageCommand->setRn('RN-' . (1000 + $index));
        $imageCommand->setCarpetDesignOrder($order);

        $this->entityManager->persist($imageCommand);
    }

    private function generateComment(string $type, int $index): string
    {
        $comments = [
            'commercial' => [
                'Customer requested urgent delivery',
                'Special color requirements',
                'High priority order',
                'VIP customer - handle with care'
            ],
            'adv' => [
                'Need confirmation by tomorrow',
                'Technical constraints apply',
                'Verify measurements before production',
                'Special finishing required'
            ],
            'studio' => [
                'Design approved by client',
                'Minor adjustments needed',
                'Waiting for final confirmation',
                'Pattern needs optimization'
            ]
        ];

        $prefix = match ($type) {
            'commercial' => 'COM',
            'adv' => 'ADV',
            'studio' => 'STU',
            default => 'GEN'
        };

        return sprintf(
            '%s #%s-%d',
            $comments[$type][array_rand($comments[$type])],
            $prefix,
            $index + 1
        );
    }
}