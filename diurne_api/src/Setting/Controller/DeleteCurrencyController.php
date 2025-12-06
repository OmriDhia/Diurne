<?php

namespace App\Setting\Controller;

use App\Setting\Entity\Currency;
use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Currency\DeleteCurrencyCommand;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/currency/{id}', name: 'delete_currency', methods: ['DELETE'])]
#[OA\Response(
    response: 200,
    description: 'Currency deleted',
    content: new Model(type: Currency::class)
)]
#[OA\Tag(name: 'Setting')]
class DeleteCurrencyController extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteCurrencyCommand($id);

        try {
            $response = $this->handle($deleteCommand);
            return SuccessResponse::create(
                'currency_delete',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
