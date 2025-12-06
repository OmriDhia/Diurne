<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\Contremarque\DetachContactFromContremarqueCommand;
use App\Contremarque\DTO\DetachContactToContremarqueRequestDto;
use App\Contremarque\Entity\ContremarqueContact;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class DetachContactFromContremarqueController extends CommandQueryController
{
    #[Route('/api/detach/Contact', name: 'detach_contact_from_contremarque', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Detach contact to contremarque',
        content: new Model(type: ContremarqueContact::class)
    )]
    #[OA\RequestBody(
        description: 'ContremarqueContact data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'contremarqueId', type: 'integer'),
                new OA\Property(property: 'contactId', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapRequestPayload] DetachContactToContremarqueRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $detachContactToContremarqueCommand = new DetachContactFromContremarqueCommand(
            $requestDTO->contremarqueId,
            $requestDTO->contactId
        );
        $carpetTypeResponse = $this->handle($detachContactToContremarqueCommand);

        return SuccessResponse::create(
            'detach_contact_from_contremarque',
            $carpetTypeResponse->toArray(),
            'detach_contact_from_contremarque'
        );
    }
}
