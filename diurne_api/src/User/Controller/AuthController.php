<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\User\Bus\Command\SignIn\SignInCommand;
use App\User\DTO\SignInRequestDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[Route('/api/authenticate', name: 'authenticate_user', methods: ['POST'])]
#[OA\Post(
    path: '/api/authenticate',
    summary: 'Login user',
    tags: ['Authentication'],
    requestBody: new OA\RequestBody(
        description: 'User credentials',
        required: true,
        content: new OA\JsonContent(
            type: 'object',
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
                type: 'object',
                properties: [
                    new OA\Property(property: 'token', type: 'string'),
                    new OA\Property(property: 'expires_in', type: 'integer', description: 'Expiration time in minutes'),
                ]
            )
        ),
        new OA\Response(
            response: 401,
            description: 'Invalid credentials'
        ),
    ],
    security: [['Bearer' => []]]
)]
class AuthController extends CommandQueryController
{
    public function __invoke(
        #[MapRequestPayload] SignInRequestDto $requestDTO,
    ): JsonResponse {
        $signInCommand = new SignInCommand($requestDTO->email, $requestDTO->password);
        $apiToken = $this->handle($signInCommand);

        return SuccessResponse::create('authenticate_user', $apiToken->toArray(), 'This token expire in 1440 minutes soit 24 heures');
    }
}
