<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\UserMobileApp;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Query\User\GetUserMobileApp\GetUserMobileAppQuery;
use App\MobileAppApi\Entity\UserMobileApp;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetUserMobileAppController extends CommandQueryController
{
    #[Route('/api/mobile/users/{id}', name: 'get_mobile_user', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'User details',
        content: new Model(type: UserMobileApp::class)
    )]
    public function __invoke(int $id): JsonResponse
    {
        $query = new GetUserMobileAppQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_mobile_user',
            $response->toArray(),
            'User details retrieved successfully'
        );
    }
}
