<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\UserMobileApp;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\MobileApp\User\CreateUserMobileAppCommand;
use App\MobileAppApi\Bus\Command\MobileApp\User\UpdateUserMobileAppCommand;
use App\MobileAppApi\Bus\Command\MobileApp\User\DeleteUserMobileAppCommand;
use App\MobileAppApi\Bus\Query\MobileApp\User\GetUserMobileAppQuery;
use App\MobileAppApi\Bus\Query\MobileApp\User\GetUsersMobileAppQuery;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use App\MobileAppApi\Entity\UserMobileApp;

class UserMobileAppController extends CommandQueryController
{
    #[Route('/api/mobile/users', name: 'create_mobile_user', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'User creation',
        content: new Model(type: UserMobileApp::class)
    )]
    #[OA\RequestBody(
        description: 'User data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'email', type: 'string'),
                new OA\Property(property: 'password', type: 'string'),
                new OA\Property(property: 'permissionId', type: 'integer'),
                new OA\Property(property: 'isActive', type: 'boolean'),
                new OA\Property(property: 'picture', type: 'string'),
            ]
        )
    )]
    public function create(
        #[MapRequestPayload] CreateUserMobileAppCommand $command
    ): JsonResponse {
        $response = $this->handle($command);

        return SuccessResponse::create(
            'create_mobile_user',
            $response->toArray(),
            'User created successfully'
        );
    }

    #[Route('/api/mobile/users/{id}', name: 'get_mobile_user', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'User details',
        content: new Model(type: UserMobileApp::class)
    )]
    public function get(int $id): JsonResponse
    {
        $query = new GetUserMobileAppQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_mobile_user',
            $response->toArray(),
            'User details retrieved successfully'
        );
    }

    #[Route('/api/mobile/users', name: 'get_mobile_users', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of users',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: UserMobileApp::class))
        )
    )]
    public function getAll(): JsonResponse
    {
        $query = new GetUsersMobileAppQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_mobile_users',
            $response->toArray(),
            'Users retrieved successfully'
        );
    }

    #[Route('/api/mobile/users/{id}', name: 'update_mobile_user', methods: ['PUT', 'PATCH'])]
    #[OA\Response(
        response: 200,
        description: 'User update',
        content: new Model(type: UserMobileApp::class)
    )]
    #[OA\RequestBody(
        description: 'User data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'email', type: 'string'),
                new OA\Property(property: 'permissionId', type: 'integer'),
                new OA\Property(property: 'isActive', type: 'boolean'),
                new OA\Property(property: 'picture', type: 'string'),
            ]
        )
    )]
    public function update(
        int $id,
        #[MapRequestPayload] UpdateUserMobileAppCommand $command
    ): JsonResponse {
        // We need to inject ID into command since MapRequestPayload maps body only usually, 
        // but for simplicity we assume client sends body matching command. 
        // Actually, best practice is DTO. Controller maps ID manually or Command has setter.
        // Or we construct command manually.
        // However, MapRequestPayload is sticky.
        // Better:
        $command = new UpdateUserMobileAppCommand(
            $id,
            $command->name,
            $command->email,
            $command->isActive,
            $command->permissionId,
            $command->picture
        );

        $response = $this->handle($command);
        return SuccessResponse::create('update_mobile_user', $response->toArray(), 'User updated');
    }

    #[Route('/api/mobile/users/{id}', name: 'delete_mobile_user', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'User deletion'
    )]
    public function delete(int $id): JsonResponse
    {
        $command = new DeleteUserMobileAppCommand($id);
        $this->handle($command);

        return SuccessResponse::create('delete_mobile_user', [], 'User deleted successfully');
    }
}
