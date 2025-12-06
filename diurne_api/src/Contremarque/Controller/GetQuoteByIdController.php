<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Bus\Command\CommandBus;
use App\Common\Bus\Query\QueryBus;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\ConvertAndCalculate\ConvertAndCalculateCommand;
use App\Contremarque\Bus\Query\GetQuoteById\GetQuoteByIdQuery;
use App\Contremarque\Entity\Contremarque;
use App\Contremarque\Repository\QuoteRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class GetQuoteByIdController extends CommandQueryController
{
    public function __construct(
        private readonly QuoteRepository $quoteRepository,
        private readonly QueryBus        $queryBus,
        private readonly CommandBus      $commandBus
    )
    {
        parent::__construct($queryBus, $commandBus);
    }


    #[Route('/api/quote/{id}', name: 'get_quote_by_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Contremarque retrieval',
        content: new Model(type: Contremarque::class)
    )]
    #[OA\Tag(name: 'Devis')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $getQuoteByIdQuery = new GetQuoteByIdQuery($id);
        $response = $this->ask($getQuoteByIdQuery);

        $quoteObject = $this->quoteRepository->find($id);
        if (!$quoteObject) {
            return new JsonResponse(['code' => 404, 'message' => 'Quote not found'], 404);
        }

        $conversionData = [];

        $quoteDetails = $quoteObject->getQuoteDetails()->toArray();

        /* if ($quoteDetails) {

             foreach ($quoteObject->getQuoteDetails() as $quoteDetail) {
                 if (!$quoteDetail->isActive()) {
                     continue;
                 }

                 $carpetSpec = $quoteDetail->getCarpetSpecification();
                 if (!$carpetSpec) {
                     continue;
                 }

                 $measurements = [
                     'largCm' => null,
                     'lngCm' => null,
                     'largFeet' => null,
                     'lngFeet' => null,
                     'largInches' => null,
                     'lngInches' => null
                 ];

                 foreach ($carpetSpec->getCarpetDimensions() as $dimensions) {
                     if (!$dimensions) {
                         continue;
                     }
                     $values = $dimensions->getDimensionValues();
                     $measurements = $this->extractMeasurements($values);
                 }

                 if (
                     null === $measurements['largCm']
                     && null === $measurements['largFeet']
                     && null === $measurements['largInches']
                 ) {
                     continue;
                 }

                 $inputUnit = null !== $measurements['largCm'] ? 'cm' : 'ft';

                 $convertCommand = new ConvertAndCalculateCommand(
                     $measurements['largCm'],
                     $measurements['lngCm'] ?? $measurements['largCm'],
                     $measurements['largFeet'],
                     $measurements['lngFeet'] ?? $measurements['largFeet'],
                     $measurements['largInches'],
                     $measurements['lngInches'] ?? $measurements['largInches'],
                     $inputUnit,
                     $quoteDetail->getId(),
                     null,
                     $quoteObject->getCurrency()->getId()
                 );

                 $conversionData[$quoteDetail->getId()] = $this->handle($convertCommand)->toArray();

                 unset($carpetSpec, $dimensions, $values, $measurements);
                 gc_collect_cycles();
             }

         }*/

        return SuccessResponse::create(
            'get_quote_by_id',
            $response
        );
    }

    private function extractMeasurements(iterable $values): array
    {
        $measurements = [
            'largCm' => null,
            'lngCm' => null,
            'largFeet' => null,
            'lngFeet' => null,
            'largInches' => null,
            'lngInches' => null
        ];

        foreach ($values as $value) {
            $unit = $value->getUnit()->getAbbreviation();
            $val = (float)$value->getValue();

            switch ($unit) {
                case 'cm':
                    $measurements['largCm'] = $val;
                    break;
                case 'ft':
                    $measurements['largFeet'] = $val;
                    break;
                case 'inch':
                    $measurements['largInches'] = $val;
                    break;
            }
        }

        return $measurements;
    }
}
