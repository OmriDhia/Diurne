<?php

declare(strict_types=1);

namespace App\Workshop\Controller\WorkshopInformation;

use App\Common\Bus\Command\CommandBus;
use App\Common\Bus\Query\QueryBus;
use App\Common\Calculator\Dimension\DimensionCalculatorInterface;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Repository\ManufacturerPriceGridRepository;
use App\Setting\Repository\ManufacturerPriceRepository;
use App\Setting\Repository\MaterialRepository;
use App\Workshop\Calculator\Calculator;
use App\Workshop\Calculator\Price\WorkshopPriceCalculator;
use App\Workshop\DTO\WorkshopInformation\CalculatePricesRequestDto;
use App\Workshop\DTO\WorkshopInformation\CalculatePricesResponse;
use App\Workshop\Entity\WorkshopInformation;
use App\Workshop\Repository\MaterialPurchasePriceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

class CalculatePricesController extends CommandQueryController
{
    public function __construct(
        QueryBus                                         $queryBus,
        CommandBus                                       $commandBus,
        private readonly EntityManagerInterface          $entityManager,
        private readonly Calculator                      $calculator,
        private readonly MaterialPurchasePriceRepository $materialPurchasePriceRepository,
        private readonly ManufacturerPriceGridRepository $manufacturerPriceGridRepository,
        private readonly ManufacturerPriceRepository     $manufacturerPriceRepository,
        private readonly MaterialRepository              $materialRepository,
        private readonly DimensionCalculatorInterface    $dimensionCalculator
    )
    {
        parent::__construct($queryBus, $commandBus);
    }

    #[Route('/api/workshop/calculatePrices', name: 'api_workshop_calculate_prices', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Successfully calculated and saved carpet purchase prices',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'prices',
                    type: 'object',
                    ref: new Model(type: CalculatePricesResponse::class)
                ),
            ],
            type: 'object'
        )
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'idWorkshopInformation', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(#[MapRequestPayload] CalculatePricesRequestDto $requestDto): JsonResponse
    {
        // Allows unauthorized access
        $this->entityManager->beginTransaction();

        try {

            $workshopInformation = $this->entityManager->getRepository(WorkshopInformation::class)
                ->find($requestDto->idWorkshopInformation);
            if (!$workshopInformation) {
                throw new \Exception('Workshop information not found');
            }

            // Ensure entity and relations are initialized (avoid lazy proxy surprises)
            if (method_exists($this->entityManager, 'initializeObject')) {
                $this->entityManager->initializeObject($workshopInformation);
            }

            $currency = $workshopInformation->getCurrency();
            if (!$currency) {
                throw new \Exception('Currency not defined for workshop information');
            }

            if (method_exists($this->entityManager, 'initializeObject')) {
                $this->entityManager->initializeObject($currency);
            }

            $priceCalculator = new WorkshopPriceCalculator(
                $workshopInformation,
                $this->materialPurchasePriceRepository,
                $this->manufacturerPriceGridRepository,
                $this->manufacturerPriceRepository,
                $this->materialRepository,
                $currency,
                null // manualUpchargePercentage if needed
            );

            // Ensure calculator has both price and dimension calculators
            $this->calculator
                ->setPriceCalculator($priceCalculator)
                ->setDimensionCalculator($this->dimensionCalculator);

            $this->calculator->calculateAndPersist($workshopInformation);

            // Fetches prices after persistence. Guard against null returns from the calculator.
            $priceHtPerMeter = $this->calculator->getPriceHtPerMeter('min');
            $pricePerM2 = $priceHtPerMeter ? $priceHtPerMeter->getTaxExcluded() : 0;

            $purchasePriceTotal = $this->calculator->getPurchasePriceTotal();
            $priceCmd = $purchasePriceTotal ? $purchasePriceTotal->getTaxExcluded() : 0;

            $purchasePricePerMeter = $this->calculator->getPurchasePricePerMeter();
            $priceTheoretical = $purchasePricePerMeter ? $purchasePricePerMeter->getTaxExcluded() : 0;

            $this->entityManager->commit();

            $response = new CalculatePricesResponse($pricePerM2, $priceCmd, $priceTheoretical);
            return SuccessResponse::create('calculate_prices', ['prices' => $response->toArray()]);
        } catch (\Throwable $e) {
            // Rollback and return detailed error information for debugging.
            try {
                $this->entityManager->rollback();
            } catch (\Throwable $rollbackEx) {
                // swallow rollback exceptions but include them in the response if needed
            }

            $payload = [
                'code' => 500,
                'message' => 'Price calculation failed',
                'error' => $e->getMessage(),
                'exception_class' => \get_class($e),
            ];

            if (method_exists($e, 'getTraceAsString')) {
                $payload['trace'] = $e->getTraceAsString();
            }

            error_log(sprintf("Price calculation error: %s: %s\n%s", get_class($e), $e->getMessage(), $payload['trace'] ?? ''));

            return new JsonResponse($payload, 500);
        }
    }
}
