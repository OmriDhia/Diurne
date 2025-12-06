<?php

namespace App\Workshop\Command;

use App\ProgressReport\Repository\ProgressReportRepository;
use App\Workshop\Entity\WorkshopInformation;
use App\Workshop\Entity\WorkshopOrder;
use App\Contremarque\Entity\ImageCommand\ImageCommand;
use App\Contremarque\Repository\ImageCommandRepository;
use App\Workshop\Repository\MaterialPurchasePriceRepository;
use App\Workshop\Repository\WorkshopInformationRepository;
use App\Workshop\Repository\WorkshopOrderRepository;
use App\ProgressReport\Repository\ProvisionalCalendarRepository;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-workshop-fixtures',
    description: 'Create workshop information and order fixtures'
)]
class CreateWorkshopFixturesCommand extends Command
{
    private const FIXTURE_COUNT = 100;
    private const BATCH_SIZE = 20;

    public function __construct(
        private readonly EntityManagerInterface          $entityManager,
        private readonly WorkshopInformationRepository   $informationRepository,
        private readonly WorkshopOrderRepository         $orderRepository,
        private readonly ImageCommandRepository          $imageCommandRepository,
        private readonly ProvisionalCalendarRepository   $calendarRepository,
        private readonly ProgressReportRepository        $progressReportRepository,
        private readonly MaterialPurchasePriceRepository $materialPurchasePriceRepository,

    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');

        $this->clearExistingData($io);
        $this->createFixtures($io);

        return Command::SUCCESS;
    }

    private function clearExistingData(SymfonyStyle $io): void
    {
        $reports = $this->progressReportRepository->findAll();
        foreach ($reports as $report) {
            $this->entityManager->remove($report);
        }
        $this->entityManager->flush();

        $calendars = $this->calendarRepository->findAll();
        foreach ($calendars as $calendar) {
            $this->calendarRepository->remove($calendar);
        }
        $prices = $this->materialPurchasePriceRepository->findAll();
        foreach ($prices as $price) {
            $this->materialPurchasePriceRepository->remove($price);
        }

        $orders = $this->orderRepository->findAll();
        foreach ($orders as $order) {
            $order->setWorkshopInformation(null);
            $this->entityManager->remove($order);
        }
        $this->entityManager->flush();

        $infos = $this->informationRepository->findAll();
        foreach ($infos as $info) {
            $this->entityManager->remove($info);
        }
        $this->entityManager->flush();

        if ($orders || $infos) {
            $io->success('Existing workshop data cleared.');
        }
    }

    private function createFixtures(SymfonyStyle $io): void
    {
        $io->note(sprintf('Creating %d WorkshopInformation and WorkshopOrder entities', self::FIXTURE_COUNT));


        $imageCommands = $this->imageCommandRepository->findAll();

        if (empty($imageCommands)) {
            $io->warning('No ImageCommand entities found. Please run app:image-command-fixtures first.');
        }

        $io->progressStart(self::FIXTURE_COUNT);

        for ($i = 0; $i < self::FIXTURE_COUNT; $i++) {
            $info = $this->generateWorkshopInformation($i);
            $this->entityManager->persist($info);


            $imageCommand = $imageCommands[$i] ?? null;
            $order = $this->generateWorkshopOrder($i, $info, $imageCommand);

            $this->entityManager->persist($order);

            if (0 === ($i + 1) % self::BATCH_SIZE) {
                $this->entityManager->flush();
            }

            $io->progressAdvance();
        }

        $this->entityManager->flush();
        $io->progressFinish();

        $io->success(sprintf('Created %d workshop fixtures.', self::FIXTURE_COUNT));
    }

    private function generateWorkshopInformation(int $index): WorkshopInformation
    {
        $info = new WorkshopInformation();

        $launchDate = new DateTimeImmutable();
        $expectedEnd = $launchDate->add(new DateInterval('P' . random_int(5, 20) . 'D'));

        $width = random_int(100, 300) / 10;
        $height = random_int(100, 300) / 10;
        $realWidth = $width - random_int(0, 10) / 10;
        $realHeight = $height - random_int(0, 10) / 10;

        $info->setLaunchDate($launchDate);
        $info->setExpectedEndDate($expectedEnd);
        $info->setProductionTime(random_int(1, 30));
        $info->setOrderSilkPercentage((string)random_int(0, 100));
        $info->setOrderedWidth(number_format($width, 2, '.', ''));
        $info->setOrderedHeigh(number_format($height, 2, '.', ''));
        $info->setOrderedSurface(number_format($width * $height, 2, '.', ''));
        $info->setRealWidth(number_format($realWidth, 2, '.', ''));
        $info->setRealHeight(number_format($realHeight, 2, '.', ''));
        $info->setRealSurface(number_format($realWidth * $realHeight, 2, '.', ''));
        $info->setReductionRate(number_format(random_int(0, 10) / 100, 2, '.', ''));
        $info->setHasComplixityWorkshop((bool)random_int(0, 1));
        $info->setHasMultilevelWorkshop((bool)random_int(0, 1));
        $info->setHasSpecialShape((bool)random_int(0, 1));
        $info->setCarpetPurchasePricePerM2(number_format(random_int(50, 300), 2, '.', ''));
        $info->setCarpetPurchasePriceCmd(number_format(random_int(50, 300), 2, '.', ''));
        $info->setCarpetPurchasePriceTheoretical(number_format(random_int(50, 300), 2, '.', ''));
        $info->setCarpetPurchasePriceInvoice(number_format(random_int(50, 300), 2, '.', ''));
        $info->setPenalty(number_format(random_int(0, 10), 2, '.', ''));
        $info->setShipping(number_format(random_int(0, 10), 2, '.', ''));
        $info->setTva(number_format(random_int(0, 20), 2, '.', ''));
        $info->setAvailableForSale((bool)random_int(0, 1));
        $info->setSent((bool)random_int(0, 1));
        $info->setReceivedInParis((bool)random_int(0, 1));
        $info->setSpecialRate((bool)random_int(0, 1));
        $info->setGrossMargin(number_format(random_int(0, 50), 2, '.', ''));
        $info->setReferenceOnInvoice('INV-' . sprintf('%04d', $index + 1));
        $info->setInvoiceNumber('NUM-' . sprintf('%04d', $index + 1));
        $info->setManufacturerId(random_int(1, 5));
        $info->setCopy(1);
        $info->setRn('RN-' . sprintf('%05d', $index + 1));

        return $info;
    }


    private function generateWorkshopOrder(int $index, WorkshopInformation $info, ?ImageCommand $imageCommand): WorkshopOrder

    {
        $order = new WorkshopOrder();
        $order->setReference('workShop-' . sprintf('%04d', $index + 1));
        $order->setCreatedAt(new DateTimeImmutable());
        $order->setUpdatedAt(new DateTimeImmutable());

        if ($imageCommand !== null) {
            $order->setImageCommand($imageCommand);
        }

        $order->setWorkshopInformation($info);

        return $order;
    }
}
