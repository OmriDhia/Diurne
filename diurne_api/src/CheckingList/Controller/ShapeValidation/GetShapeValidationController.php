<?php

namespace App\CheckingList\Controller\ShapeValidation;

use App\CheckingList\Bus\Query\GetShapeValidation\GetShapeValidationQuery;
use App\CheckingList\Entity\ShapeValidation;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetShapeValidationController extends CommandQueryController
{
    #[Route('/api/shapeValidations', name: 'shape_validation_list', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'List', content: new OA\JsonContent(properties: [new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: new Model(type: ShapeValidation::class)))]))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetShapeValidationQuery();
        $response = $this->ask($query);

        return SuccessResponse::create('shape_validation_list', $response->toArray());
    }
}
