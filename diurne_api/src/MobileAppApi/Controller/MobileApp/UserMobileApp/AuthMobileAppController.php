<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\UserMobileApp;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\MobileApp\Auth\SignInMobileAppCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class AuthMobileAppController extends CommandQueryController
{
    #[Route('/api/mobile/authenticate', name: 'authenticate_mobile_user', methods: ['POST'])]
    #[OA\Post(
        path: '/api/mobile/authenticate',
        summary: 'Login mobile user',
        tags: ['Mobile Authentication'],
        requestBody: new OA\RequestBody(
            description: 'User credentials',
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'email', type: 'string'),
                    new OA\Property(property: 'password', type: 'string'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'User logged in successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token', type: 'string'),
                        new OA\Property(property: 'user_id', type: 'integer'),
                    ]
                )
            )
        ]
    )]
    public function __invoke(
        #[MapRequestPayload] SignInMobileAppCommand $command
    ): JsonResponse {
        $apiToken = $this->handle($command);

        return SuccessResponse::create('authenticate_mobile_user', $apiToken->toArray(), 'Authenticated successfully');
    }
}
