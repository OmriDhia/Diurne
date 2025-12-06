<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\TransportCondition\CreateTransportConditionCommand;
use App\Setting\Bus\Command\TransportCondition\TransportConditionResponse;
use App\Setting\Bus\Command\TransportConditionLang\CreateTransportConditionLangCommand;
use App\Setting\Bus\Command\TransportConditionLang\TransportConditionLangResponse;
use App\Setting\DTO\CreateTransportConditionRequestDto;
use App\Setting\Entity\TransportCondition;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/transportCondition/create', name: 'transportCondition_creation', methods: ['POST'])]
class CreateTransportConditionController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'TransportCondition creation',
        content: new Model(type: TransportCondition::class)
    )]
    #[OA\RequestBody(
        description: 'TransportCondition data',
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(
                    property: 'languages',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
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
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateTransportConditionRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCommand = new CreateTransportConditionCommand(
            $requestDTO->name,
        );
        /** @var TransportConditionResponse $transportConditionResponse */
        $transportConditionResponse = $this->handle($createCommand);
        if (!empty($requestDTO->languages)) {
            foreach ($requestDTO->languages as $language) {
                $createCommandLang = new CreateTransportConditionLangCommand(
                    $transportConditionResponse->getTransportConditionId(),
                    $language->label,
                    $language->description,
                    $language->lang_id
                );
                /** @var TransportConditionLangResponse $transportCondition */
                $transportConditionLangResponse = $this->handle($createCommandLang);
                $transportConditionResponse->addLanguage($transportConditionLangResponse);
            }
        }

        return SuccessResponse::create(
            'transportCondition_creation',
            $transportConditionResponse->toArray(),
            'TransportCondition created successfully'

        );
    }
}
