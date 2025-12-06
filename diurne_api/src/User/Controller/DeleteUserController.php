<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\User\Bus\Command\DeleteUser\DeleteUserCommand;
use App\User\Bus\Command\DeleteUser\DeleteUserResponse;
use App\User\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DeleteUserController extends CommandQueryController
{
    #[Route('/api/user/{id}', name: 'user_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'User deleted',
        content: new Model(type: User::class)
    )]
    #[OA\Tag(name: 'User')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'user')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $command = new DeleteUserCommand($id);
        /** @var DeleteUserResponse $response */
        $response = $this->handle($command);

        return SuccessResponse::create(
            'user_delete',
            $response->toArray(),
            'User deleted successfully'
        );
    }
}
