<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Bus\Command\CommandBus;
use App\Common\Bus\Query\QueryBus;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Contremarque\DTO\CalculateQuotePriceRequestDto;
use App\Contremarque\Bus\Command\ConvertAndCalculate\ConvertAndCalculateCommand;
use App\Contremarque\Repository\QuoteRepository;
use RuntimeException;

class CalculateQuotePricesController extends CommandQueryController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly QuoteRepository $quoteRepository,
        private readonly QueryBus $queryBus,
        private readonly CommandBus $commandBus
    ) {
        parent::__construct($queryBus, $commandBus);
    }
    #[Route('/api/calculate/quote/{quoteId}', name: 'calculate_quote_price', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Calculate quote prices',
        content: new Model(type: CalculateQuotePriceRequestDto::class)
    )]
    #[OA\RequestBody(
        description: 'Prices data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'additionalDiscount', type: 'float'),
                new OA\Property(property: 'shippingPrice', type: 'float'),
            ]
        )
    )]
    #[OA\Tag(name: 'Devis')]
    public function __invoke(
        $quoteId,
        #[MapRequestPayload] CalculateQuotePriceRequestDto $requestDTO
    ): JsonResponse {

        $quote = $this->quoteRepository->find((int)$quoteId);

        if (!$quote) {
            throw new RuntimeException("Quote with ID {$quoteId} not found.");
        }

        // Apply the additional discount if it's set
        if ($requestDTO->additionalDiscount !== null) {
            $quote->setAdditionalDiscount((string)$requestDTO->additionalDiscount);
        }

        // Apply the shipping price if it's set
        if ($requestDTO->shippingPrice !== null) {
            $quote->setShippingPrice((string)$requestDTO->shippingPrice);
        }
        // Persist the updated quote
        $this->entityManager->persist($quote);
        $this->entityManager->flush();

        $quoteDetails = $quote->getQuoteDetails();

        foreach ($quoteDetails as $quoteDetail) {
            if (!$quoteDetail->isActive()) {
                continue;
            }
            $dimensions = $quoteDetail->extractDimensionsInCm();
            $convertCommand = new ConvertAndCalculateCommand(
                (float)$dimensions['Largeur'],
                (float)$dimensions['Longueur'],
                null,
                null,
                null,
                null,
                'cm',
                $quoteDetail->getId(),
                null,
                $quoteDetail->getCurrency() ? $quoteDetail->getCurrency()->getId() : null,
            );
            $this->handle($convertCommand);
        }


        return SuccessResponse::create(
            'calculate_quote_price',
            $quote->toArray()
        );
    }
}
