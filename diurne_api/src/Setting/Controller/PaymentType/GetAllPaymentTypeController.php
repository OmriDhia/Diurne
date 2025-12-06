<?php

namespace App\Setting\Controller\PaymentType;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\PaymentType\GetAllPaymentTypeQuery;
use App\Setting\Entity\PaymentType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/payment-type', name: 'get_all_payment_types', methods: ['GET'])]
class GetAllPaymentTypeController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Fetch all available Payment types',
        content: new Model(type: PaymentType::class)
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(): JsonResponse
    {
        // Authorization check
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetAllPaymentTypeQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_all_payment_types',
            $response->toArray()
        );
    }
}
