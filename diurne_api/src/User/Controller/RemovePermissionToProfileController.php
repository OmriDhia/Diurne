<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\User\Bus\Command\Permission\RemovePermissionToProfileCommand;
use App\User\DTO\RemovePermissionToProfileRequestDto;
use App\User\Entity\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class RemovePermissionToProfileController extends CommandQueryController
{
    #[Route('/api/removePermissionFromProfile', name: 'remove_permission_from_profile', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Remove permission from Profile',
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
        #[MapRequestPayload] RemovePermissionToProfileRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('update', 'profile')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $givePermissionToProfileCommand = new RemovePermissionToProfileCommand($requestDTO->permissionId, $requestDTO->profileId);
        $profilePermissionResponse = $this->handle($givePermissionToProfileCommand);

        return SuccessResponse::create(
            'give_permission_to_profile',
            $profilePermissionResponse->toArray(),
            'Permission removed from profile successfully.'

        );
    }
}
