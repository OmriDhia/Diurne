<?php

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\DeleteImage\DeleteImageCommand;
use App\Contremarque\DTO\DeleteImageRequestDto;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DeleteImageController extends CommandQueryController
{
    #[Route('/api/image/delete', name: 'images_delete_method', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Images deleted successfully',
        content: null
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        Request            $request,
        ValidatorInterface $validator
    ): JsonResponse
    {
        if (!$this->isGranted('delete', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['imageIds']) || !is_array($data['imageIds'])) {
            return new JsonResponse(['error' => 'Invalid or missing imageIds'], 400);
        }

        $dto = new DeleteImageRequestDto($data['imageIds']);

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string)$errors], 400);
        }

        $deleteImagesCommand = new DeleteImageCommand($dto->getImageIds());
        $response = $this->handle($deleteImagesCommand);
        return SuccessResponse::create(
            'images_delete_method',
            $response->toArray()
        );
    }
}