<?php

namespace App\Workshop\Controller\WorkshopInformation;

use DateTime;
use RuntimeException;
use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\UpdateWorkshopInformation\UpdateWorkshopInformationCommand;

use App\Workshop\Entity\WorkshopInformation;
use Symfony\Component\Routing\Attribute\Route;
use App\Workshop\DTO\WorkshopInformation\UpdateWorkshopInformationRequestDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

class UpdateWorkshopInformationController extends CommandQueryController
{
    #[Route('/api/workshopInformation/{id}', name: 'workshop_information_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Workshop information updated successfully',
        content: new Model(type: WorkshopInformation::class)
    )]
    #[OA\RequestBody(
        description: 'Workshop information data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "launch_date", type: "string", format: "date"),
                new OA\Property(property: "expected_end_date", type: "string", format: "date-time"),
                new OA\Property(property: "date_end_atelier_prev", type: "string", format: "date"),
                new OA\Property(property: "production_time", type: "integer"),
                new OA\Property(property: "order_silk_percentage", type: "string"),
                new OA\Property(property: "ordered_width", type: "string"),
                new OA\Property(property: "ordered_heigh", type: "string"),
                new OA\Property(property: "ordered_surface", type: "string"),
                new OA\Property(property: "real_width", type: "string"),
                new OA\Property(property: "real_height", type: "string"),
                new OA\Property(property: "real_surface", type: "string"),
                new OA\Property(property: "id_tarif_group", type: "integer"),
                new OA\Property(property: "reduction_rate", type: "string"),
                new OA\Property(property: "upcharge", type: "string"),
                new OA\Property(property: "commentUpcharge", type: "string"),
                new OA\Property(property: "carpet_purchase_price_per_m2", type: "string"),
                new OA\Property(property: "carpet_purchase_price_cmd", type: "string"),
                new OA\Property(property: "carpet_purchase_price_theoretical", type: "string"),
                new OA\Property(property: "carpet_purchase_price_invoice", type: "string"),
                new OA\Property(property: "penalty", type: "string"),
                new OA\Property(property: "shipping", type: "string"),
                new OA\Property(property: "tva", type: "string"),
                new OA\Property(property: "availableForSale", type: "boolean"),
                new OA\Property(property: "sent", type: "boolean"),
                new OA\Property(property: "receivedInParis", type: "boolean"),
                new OA\Property(property: "specialRate", type: "boolean"),
                new OA\Property(property: "gross_margin", type: "string"),
                new OA\Property(property: "reference_on_invoice", type: "string"),
                new OA\Property(property: "invoice_number", type: "string"),
                new OA\Property(property: "currencyId", type: "integer"),
                new OA\Property(property: "manufacturer_id", type: "integer"),
                new OA\Property(property: "idQuality", type: "integer"),
                new OA\Property(property: "idTarifGroup", type: "integer"),
                new OA\Property(property: "copy", type: "integer"),
                new OA\Property(property: "Rn", type: "string")
            ]
        )
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(
        int                                                      $id,
        #[MapRequestPayload] UpdateWorkshopInformationRequestDto $requestDto
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);

        }

        $launchDate = $this->parseDate($requestDto->launchDate, 'Y-m-d', true, true);
        $expectedEndDate = $this->parseDate($requestDto->expectedEndDate, 'Y-m-d H:i:s', true);
        $dateEndAtelierPrev = $this->parseDate($requestDto->dateEndAtelierPrev, 'Y-m-d', true, true);

        $command = new UpdateWorkshopInformationCommand(
            id: $id,
            launchDate: $launchDate,
            expectedEndDate: $expectedEndDate,
            dateEndAtelierPrev: $dateEndAtelierPrev,
            productionTime: $requestDto->productionTime,
            orderSilkPercentage: $requestDto->orderSilkPercentage,
            orderedWidth: $requestDto->orderedWidth,
            orderedHeigh: $requestDto->orderedHeigh,
            orderedSurface: $requestDto->orderedSurface,
            realWidth: $requestDto->realWidth,
            realHeight: $requestDto->realHeight,
            realSurface: $requestDto->realSurface,
            idTarifGroup: $requestDto->idTarifGroup,
            reductionRate: $requestDto->reductionRate,
            upcharge: $requestDto->upcharge,
            commentUpcharge: $requestDto->commentUpcharge,
            carpetPurchasePricePerM2: $requestDto->carpetPurchasePricePerM2,
            carpetPurchasePriceCmd: $requestDto->carpetPurchasePriceCmd,
            carpetPurchasePriceTheoretical: $requestDto->carpetPurchasePriceTheoretical,
            carpetPurchasePriceInvoice: $requestDto->carpetPurchasePriceInvoice,
            penalty: $requestDto->penalty,
            shipping: $requestDto->shipping,
            tva: $requestDto->tva,
            availableForSale: $requestDto->availableForSale,
            sent: $requestDto->sent,
            receivedInParis: $requestDto->receivedInParis,
            specialRate: $requestDto->specialRate,
            grossMargin: $requestDto->grossMargin,
            referenceOnInvoice: $requestDto->referenceOnInvoice,
            invoiceNumber: $requestDto->invoiceNumber,
            currencyId: $requestDto->currencyId,
            manufacturerId: $requestDto->manufacturerId,
            idQuality: $requestDto->idQuality,
            copy: $requestDto->copy,
            rn: $requestDto->Rn
        );
        try {
            $workshopInformation = $this->handle($command);

            if (!$workshopInformation) {
                throw new RuntimeException('Failed to create workshop information');
            }

            return SuccessResponse::create(
                'workshop_information_update',
                $workshopInformation->toArray(),
                'Workshop information updated successfully.'
            );
        } catch (RuntimeException $e) {
            return new JsonResponse(
                ['code' => 400, 'message' => $e->getMessage()],
                400
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
