<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\Workshop;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\Workshop\DeleteWorkshop\DeleteWorkshopCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class DeleteWorkshopController extends CommandQueryController
{
    #[Route('/api/mobile/workshops/{id}', name: 'delete_workshop', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Delete Workshop'
    )]
    public function __invoke(int $id): JsonResponse
    {
        $command = new DeleteWorkshopCommand($id);
        $this->handle($command);

        return SuccessResponse::create(
            'delete_workshop',
            [],
            'Workshop deleted successfully'
        );
    }
}
