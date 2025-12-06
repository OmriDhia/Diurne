<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Contremarque\Controller\OA\Response;
use App\Contremarque\Controller\OA\Tag;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CarpetComposition\Layer\DeleteLayerCommand;
use App\Contremarque\DTO\DeleteLayerRequestDto;
use App\Contremarque\Entity\Layer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;

class DeleteLayerController extends CommandQueryController
{
    #[Route('/api/CarpetComposition/{carpetCompositionId}/Layers/delete', name: 'layers_delete_method', methods: ['DELETE'])]
    #[Response(
        response: 200,
        description: 'Layer deleted successfully',
        content: null
    )]
    #[Tag(name: 'Contremarque')]
    public function __invoke(
        int $carpetCompositionId,
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        if (!$this->isGranted('delete', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $data = json_decode($request->getContent(), true);

        // Vérification que 'layerIds' est bien présent dans les données désérialisées
        if (!isset($data['layerIds']) || !is_array($data['layerIds'])) {
            return new JsonResponse(['error' => 'Invalid or missing layerIds'], 400);
        }

        // Création du DTO avec les données extraites
        $dto = new DeleteLayerRequestDto($data['layerIds']);

        // Validation du DTO
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], 400);
        }

        // Création de la commande avec les données validées
        $deleteLayerCommand = new DeleteLayerCommand($carpetCompositionId, $dto->layerIds);

        // Exécution de la commande
        $response = $this->handle($deleteLayerCommand);

        return SuccessResponse::create(
            'layers_delete_method',
            $response->toArray(),
            'Layer deleted successfully'
        );
    }
}
