<?php

declare(strict_types=1);

namespace App\Contact\Controller\Origin;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\Contact\Origin\CreateContactOriginCommand;
use App\Contact\DTO\Origin\CreateContactOriginRequestDto;
use App\Contact\Entity\ContactOrigin;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateContactOriginController extends CommandQueryController
{
    #[Route('/api/contact-origins', name: 'contact_origin_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Contact origin creation',
        content: new Model(type: ContactOrigin::class)
    )]
    #[OA\RequestBody(
        description: 'Contact origin data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'label', type: 'string')
            ]
        )
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        #[MapRequestPayload] CreateContactOriginRequestDto $requestDto
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $createContactOriginCommand = new CreateContactOriginCommand($requestDto->label);

        /** @var ContactOrigin $newContactOrigin */
        $newContactOrigin = $this->handle($createContactOriginCommand);

        return SuccessResponse::create(
            'contact_origin_creation',
            $newContactOrigin->toArray()
        );
    }
}
