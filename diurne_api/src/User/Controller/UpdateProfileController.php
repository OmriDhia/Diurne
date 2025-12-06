<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\User\Bus\Command\Profile\UpdateProfileCommand;
use App\User\DTO\UpdateProfileRequestDto;
use App\User\Entity\Profile;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateProfileController extends CommandQueryController
{
    #[Route('/api/profile/{profileId}/update', name: 'profile_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Profile update',
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
        $profileId,
        #[MapRequestPayload] UpdateProfileRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('update', 'profile')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $updateProfileCommand = new UpdateProfileCommand($profileId);
        $updateProfileCommand->setName($requestDTO->name);
        if (!empty($requestDTO->discount)) {
            $updateProfileCommand->setDiscount((float) ($requestDTO->discount ?? 0));
        } else {
            $updateProfileCommand->setDiscount(0);
        }

        $profileResponse = $this->handle($updateProfileCommand);

        return SuccessResponse::create(
            'update_profile',
            $profileResponse->toArray(),
            'Profile updated successfully'

        );
    }
}
