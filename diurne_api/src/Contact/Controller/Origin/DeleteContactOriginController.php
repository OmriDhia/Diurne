<?php
declare(strict_types=1);

namespace App\Contact\Controller\Origin;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\Contact\Origin\DeleteContactOriginCommand;
use App\Contact\Entity\ContactOrigin;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeleteContactOriginController extends CommandQueryController
{
    #[Route('/api/contact-origins/{originId}/delete', name: 'contact_origin_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Contact origin deletion',
        content: new Model(type: ContactOrigin::class)
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(int $originId): JsonResponse
    {
        if (!$this->isGranted('delete', 'contact')) {
            return new JsonResponse(['message' => 'Unauthorized'], 401);
        }

        $command = new DeleteContactOriginCommand($originId);
        $this->handle($command);

        return SuccessResponse::create(
            'contact_origin_delete',
            ['message' => 'Contact origin deleted successfully.']
        );
    }
}