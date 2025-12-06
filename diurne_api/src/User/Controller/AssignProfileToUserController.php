<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\User\Bus\Command\Profile\AssignProfileToUserCommand;
use App\User\DTO\AssignProfileToUserRequestDto;
use App\User\Entity\Profile;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class AssignProfileToUserController extends CommandQueryController
{
    #[Route('/api/AssignProfileToUser', name: 'assign_profile_to_user', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Assign Profile to User',
        content: new Model(type: Profile::class)
    )]
    #[OA\RequestBody(
        description: 'Profile and User data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'email', type: 'string'),
                new OA\Property(property: 'profileId', type: 'int'),
            ]
        )
    )]
    #[OA\Tag(name: 'Profile')]
    public function __invoke(
        #[MapRequestPayload] AssignProfileToUserRequestDto $requestDTO
    ): JsonResponse {
        //        if (!$this->isGranted('update', 'profile')) {
        //            return new JsonResponse(["code"=> 401, "message"=> 'Unauthorized to access this content'], 401);
        //        }
        $assignProfileToUserCommand = new AssignProfileToUserCommand($requestDTO->email, $requestDTO->profileId);
        $assignProfileToUser = $this->handle($assignProfileToUserCommand);

        return SuccessResponse::create(
            'assign_profile_to_user',
            $assignProfileToUser->toArray(),

        );
    }
}
