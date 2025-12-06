<?php

declare(strict_types=1);

namespace App\Workshop\Controller\WorkshopInformation;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\DeleteWorkshopInformation\DeleteWorkshopInformationCommand;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteWorkshopInformationController extends CommandQueryController
{
    #[Route('/api/workshopInformation/{id}', name: 'workshop_information_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Workshop information deleted successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'Workshop information deleted successfully')
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Workshop information not found'
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);

        }
        try {
            $command = new DeleteWorkshopInformationCommand($id);
            $response = $this->handle($command);

            return SuccessResponse::create(
                'workshop_information_deleted',
                $response->toArray(),
                'Workshop information deleted successfully.'
            );
        } catch (\RuntimeException $e) {
            return new JsonResponse(
                ['code' => 404, 'message' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['code' => 400, 'message' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}