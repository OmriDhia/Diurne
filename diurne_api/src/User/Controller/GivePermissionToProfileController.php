<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\User\Bus\Command\Permission\GivePermissionToProfileCommand;
use App\User\DTO\GivePermissionToProfileRequestDto;
use App\User\Entity\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class GivePermissionToProfileController extends CommandQueryController
{
    #[Route('/api/givePermissionToProfile', name: 'give_permission_to_profile', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Give permission to Profile',
        content: new Model(type: Permission::class)
    )]
    #[OA\RequestBody(
        description: 'Permission and Profile data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'permissionId', type: 'int'),
                new OA\Property(property: 'profileId', type: 'int'),
            ]
        )
    )]
    #[OA\Tag(name: 'Permission')]
    public function __invoke(
        #[MapRequestPayload] GivePermissionToProfileRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('update', 'profile')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $givePermissionToProfileCommand = new GivePermissionToProfileCommand($requestDTO->permissionId, $requestDTO->profileId);
        $profilePermissionResponse = $this->handle($givePermissionToProfileCommand);

        return SuccessResponse::create(
            'give_permission_to_profile',
            $profilePermissionResponse->toArray(),
            'Permission given to profile successfully'
        );
    }
}
