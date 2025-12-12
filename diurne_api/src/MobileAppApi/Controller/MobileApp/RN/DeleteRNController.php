<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\RN;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\RN\DeleteRN\DeleteRNCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class DeleteRNController extends CommandQueryController
{
    #[Route('/api/mobile/rn/{id}', name: 'delete_rn', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Delete RN'
    )]
    public function __invoke(int $id): JsonResponse
    {
        $command = new DeleteRNCommand($id);
        $this->handle($command);

        return SuccessResponse::create(
            'delete_rn',
            [],
            'RN deleted successfully'
        );
    }
}
