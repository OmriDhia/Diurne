<?php

declare(strict_types=1);

namespace App\Contact\Controller\Origin;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\Contact\Origin\UpdateContactOriginCommand;
use App\Contact\DTO\Origin\UpdateContactOriginRequestDto;
use App\Contact\Entity\ContactOrigin;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UpdateContactOriginController extends CommandQueryController
{
    #[Route(
        '/api/contact-origins/{id}',
        name: 'update_contact_origin',
        methods: ['PUT', 'PATCH']
    )]
    #[OA\Response(
        response: 200,
        description: 'Update a ContactOrigin',
        content: new Model(type: ContactOrigin::class)
    )]
    #[OA\RequestBody(
        description: 'ContactOrigin data to update',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'label', type: 'string')
            ]
        )
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        #[MapRequestPayload] UpdateContactOriginRequestDto $requestDto,
        int                                                $id
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'contact')) {
            return new JsonResponse(['message' => 'Unauthorized'], 401);
        }

        $command = new UpdateContactOriginCommand($id, $requestDto->label);
        /** @var ContactOrigin $updatedOrigin */
        $updatedOrigin = $this->handle($command);

        return SuccessResponse::create(
            'update_contact_origin',
            $updatedOrigin->toArray()
        );
    }
}
