<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\GetLanguages\GetLanguagesQuery;
use App\Setting\Entity\Language;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetLanguagesController extends CommandQueryController
{
    #[Route('/api/languages', name: 'get_languages', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'get languages',
        content: new Model(type: Language::class)
    )]
    #[OA\RequestBody(
        description: 'Country data',
        content: new OA\JsonContent(
        ))]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
    ): JsonResponse {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $getlanguages = new GetLanguagesQuery();
        $response = $this->ask($getlanguages);

        return SuccessResponse::create(
            'get_languages',
            $response
        );
    }
}
