<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\TransportCondition\UpdateTransportConditionCommand;
use App\Setting\Bus\Command\TransportConditionLang\UpdateTransportConditionLangCommand;
use App\Setting\DTO\UpdateTransportConditionRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateTransportConditionController extends CommandQueryController
{
    #[Route('/api/transportCondition/{id}/update', name: 'transportCondition_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'TransportCondition updated successfully.',
        content: new OA\JsonContent(
            ref: new Model(type: UpdateTransportConditionRequestDto::class)
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(
                    property: 'languages',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(
                                property: 'transport_condition_lang_id',
                                type: 'integer',
                                nullable: true
                            ),
                            new OA\Property(property: 'label', type: 'string'),
                            new OA\Property(property: 'description', type: 'string'),
                            new OA\Property(property: 'lang_id', type: 'integer'),
                        ],
                        type: 'object'
                    ),
                    nullable: true
                ),
            ]
        )
    )]
    #[Security(name: null)]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateTransportConditionRequestDto $updateDto
    ): JsonResponse {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        foreach ($updateDto->languages as $language) {
            $updateCommandLang = new UpdateTransportConditionLangCommand(
                $language->transport_condition_lang_id,
                $language->label,
                $language->description,
                $language->lang_id
            );
            $this->handle($updateCommandLang);
        }

        $updateCommand = new UpdateTransportConditionCommand(
            $id,
            $updateDto->name,
        );

        $response = $this->handle($updateCommand);

        return SuccessResponse::create(
            'transportCondition_update',
            $response->toArray(),
            'TransportCondition updated successfully'

        );
    }
}
