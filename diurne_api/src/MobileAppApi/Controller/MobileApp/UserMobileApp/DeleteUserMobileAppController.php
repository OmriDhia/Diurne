<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\UserMobileApp;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\User\DeleteUserMobileApp\DeleteUserMobileAppCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class DeleteUserMobileAppController extends CommandQueryController
{
    #[Route('/api/mobile/users/{id}', name: 'delete_mobile_user', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'User deletion'
    )]
    public function __invoke(int $id): JsonResponse
    {
        $command = new DeleteUserMobileAppCommand($id);
        $this->handle($command);

        return SuccessResponse::create('delete_mobile_user', [], 'User deleted successfully');
    }
}
