<?php

namespace App\Workshop\Controller\WorkshopInformation;

use DateTime;
use RuntimeException;
use Exception;
use App\Common\Controller\CommandQueryController;


use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\CreateWorkshopInformation\CreateWorkshopInformationCommand;
use App\Workshop\DTO\WorkshopInformation\CreateWorkshopInformationRequestDto;
use App\Workshop\Entity\WorkshopInformation;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;


class CreateWorkshopInformationController extends CommandQueryController
{
    #[Route('/api/workshopInformation', name: 'workshop_information_create', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Workshop information created',
        content: new Model(type: WorkshopInformation::class)
    )]
    #[OA\RequestBody(
        description: 'Workshop information data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "launchDate", type: "string", format: "date"),
                new OA\Property(property: "expectedEndDate", type: "string", format: "date-time"),
                new OA\Property(property: "dateEndAtelierPrev", type: "string", format: "date"),
                new OA\Property(property: "productionTime", type: "integer"),
                new OA\Property(property: "orderSilkPercentage", type: "string"),
                new OA\Property(property: "orderedWidth", type: "string"),
                new OA\Property(property: "orderedHeight", type: "string"),
                new OA\Property(property: "realWidth", type: "string"),
                new OA\Property(property: "realHeight", type: "string"),
                new OA\Property(property: "realSurface", type: "string"),
                new OA\Property(property: "idTarifGroup", type: "integer"),
                new OA\Property(property: "reductionRate", type: "string"),
                new OA\Property(property: "upcharge", type: "string"),
                new OA\Property(property: "commentUpcharge", type: "string"),
                new OA\Property(property: "carpetPurchasePricePerM2", type: "string"),
                new OA\Property(property: "carpetPurchasePriceCmd", type: "string"),
                new OA\Property(property: "carpetPurchasePriceTheoretical", type: "string"),
                new OA\Property(property: "carpetPurchasePriceInvoice", type: "string"),
                new OA\Property(property: "penalty", type: "string"),
                new OA\Property(property: "shipping", type: "string"),
                new OA\Property(property: "tva", type: "string"),
                new OA\Property(property: "availableForSale", type: "boolean"),
                new OA\Property(property: "sent", type: "boolean"),
                new OA\Property(property: "receivedInParis", type: "boolean"),
                new OA\Property(property: "specialRate", type: "boolean"),
                new OA\Property(property: "grossMargin", type: "string"),
                new OA\Property(property: "referenceOnInvoice", type: "string"),
                new OA\Property(property: "invoiceNumber", type: "string"),
                new OA\Property(property: "currencyId", type: "integer"),
                new OA\Property(property: "manufacturerId", type: "integer"),
                new OA\Property(property: "idQuality", type: "integer"),
                new OA\Property(property: "copy", type: "integer", example: 1),
                new OA\Property(property: "Rn", type: "string")
            ]
        )
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(
        #[MapRequestPayload] CreateWorkshopInformationRequestDto $requestDTO
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);

        }

        $launchDate = $this->parseDate($requestDTO->launchDate, 'Y-m-d', true, true);
        $expectedEndDate = $this->parseDate($requestDTO->expectedEndDate, 'Y-m-d H:i:s', false);
        $dateEndAtelierPrev = $this->parseDate($requestDTO->dateEndAtelierPrev, 'Y-m-d', false, true);

        $command = new CreateWorkshopInformationCommand(
            launchDate: $launchDate,
            expectedEndDate: $expectedEndDate,
            dateEndAtelierPrev: $dateEndAtelierPrev,
            productionTime: $requestDTO->productionTime ?? 0,
            orderSilkPercentage: $requestDTO->orderSilkPercentage,
            orderedWidth: $requestDTO->orderedWidth,
            orderedHeigh: $requestDTO->orderedHeigh,
            orderedSurface: $requestDTO->orderedSurface,
            realWidth: $requestDTO->realWidth,
            realHeight: $requestDTO->realHeight,
            realSurface: $requestDTO->realSurface,
            idTarifGroup: $requestDTO->idTarifGroup,
            reductionRate: $requestDTO->reductionRate ?? '0.000000',
            upcharge: $requestDTO->upcharge,
            commentUpcharge: $requestDTO->commentUpcharge,
            carpetPurchasePricePerM2: $requestDTO->carpetPurchasePricePerM2,
            carpetPurchasePriceCmd: $requestDTO->carpetPurchasePriceCmd,
            carpetPurchasePriceTheoretical: $requestDTO->carpetPurchasePriceTheoretical,
            carpetPurchasePriceInvoice: $requestDTO->carpetPurchasePriceInvoice,
            penalty: $requestDTO->penalty,
            shipping: $requestDTO->shipping,
            tva: $requestDTO->tva,
            availableForSale: $requestDTO->availableForSale,
            sent: $requestDTO->sent,
            receivedInParis: $requestDTO->receivedInParis,
            specialRate: $requestDTO->specialRate,
            grossMargin: $requestDTO->grossMargin,
            referenceOnInvoice: $requestDTO->referenceOnInvoice,
            invoiceNumber: $requestDTO->invoiceNumber,
            currencyId: $requestDTO->currencyId,
            manufacturerId: $requestDTO->manufacturerId,
            copy: $requestDTO->copy,
            idQuality: $requestDTO->idQuality,
            rn: $requestDTO->Rn
        );
        try {
            $workshopInformation = $this->handle($command);

            if (!$workshopInformation) {
                throw new RuntimeException('Failed to create workshop information');
            }

            return SuccessResponse::create(
                'workshop_information_creation',
                $workshopInformation->toArray(),
                'Workshop information created successfully.'
            );
        } catch (Exception $e) {
            return new JsonResponse(
                ['code' => 500, 'message' => $e->getMessage()],
                500
            );
        }
    }

    private function parseDate(?string $value, string $format, bool $required = false, bool $resetTime = false): ?DateTime
    {
        if ($value === null) {
            if ($required) {
                throw new RuntimeException(sprintf('Missing required date value (expected format: %s).', $format));
            }

            return null;
        }

        $trimmedValue = trim($value);
        if ($trimmedValue === '') {
            if ($required) {
                throw new RuntimeException(sprintf('Missing required date value (expected format: %s).', $format));
            }

            return null;
        }

        $date = DateTime::createFromFormat($resetTime ? '!' . $format : $format, $trimmedValue);

        if ($date === false) {
            throw new RuntimeException(sprintf('Invalid date value "%s". Expected format: %s.', $trimmedValue, $format));
        }

        $errors = DateTime::getLastErrors();
        if ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) {
            throw new RuntimeException(sprintf('Invalid date value "%s". Expected format: %s.', $trimmedValue, $format));
        }

        return $date;
    }
}
