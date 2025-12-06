<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\User\Bus\Command\Profile\CreateProfileCommand;
use App\User\DTO\CreateProfileRequestDto;
use App\User\Entity\Profile;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateProfileController extends CommandQueryController
{
    #[Route('/api/createProfile', name: 'profile_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Profile creation',
        content: new Model(type: Profile::class)
    )]
    #[OA\RequestBody(
        description: 'Profile data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'discount', type: 'float'),
            ]
        )
    )]
    #[OA\Tag(name: 'Profile')]
    public function __invoke(
        #[MapRequestPayload] CreateProfileRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'profile')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $createProfileCommand = new CreateProfileCommand($requestDTO->name);
        $createProfileCommand->setDiscount((float) ($requestDTO->discount ?? 0));
        $profileResponse = $this->handle($createProfileCommand);

        return SuccessResponse::create(
            'profile_creation',
            $profileResponse->toArray(),
            "Profile created successfully"
        );
    }
}
