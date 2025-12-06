<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\Contremarque\AttachContactToContremarqueCommand;
use App\Contremarque\DTO\AttachContactToContremarqueRequestDto;
use App\Contremarque\Entity\ContremarqueContact;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class AttachContactToContremarqueController extends CommandQueryController
{
    #[Route('/api/attach/Contact', name: 'attach_contact_to_contremarque', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Attach contact to contremarque',
        content: new Model(type: ContremarqueContact::class)
    )]
    #[OA\RequestBody(
        description: 'ContremarqueContact data',
        content: new OA\JsonContent(
            properties: [new OA\Property(property: 'contremarqueId', type: 'integer'),
                new OA\Property(property: 'contactId', type: 'integer'),
                new OA\Property(property: 'current', type: 'boolean'), ]
        ))]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapRequestPayload] AttachContactToContremarqueRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $attachContactToContremarqueCommand = new AttachContactToContremarqueCommand(
            $requestDTO->contremarqueId,
            $requestDTO->contactId,
            $requestDTO->current
        );
        $carpetTypeResponse = $this->handle($attachContactToContremarqueCommand);

        return SuccessResponse::create(
            'attach_contact_to_contremarque',
            $carpetTypeResponse->toArray()
        );
    }
}
