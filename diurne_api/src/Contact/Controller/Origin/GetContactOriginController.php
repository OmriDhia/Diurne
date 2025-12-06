<?php

declare(strict_types=1);

namespace App\Contact\Controller\Origin;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Query\GetOrigin\GetContactOriginQuery;
use App\Contact\Entity\ContactOrigin;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetContactOriginController extends CommandQueryController
{
    #[Route(
        '/api/contact-origins',
        name: 'get_contact_origin',
        methods: ['GET']
    )]
    #[OA\Response(
        response: 200,
        description: 'Fetch a ContactOrigin by ID',
        content: new Model(type: ContactOrigin::class)
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'contact')) {
            return new JsonResponse(['message' => 'Unauthorized'], 401);
        }

        $query = new GetContactOriginQuery();
        $originData = $this->ask($query);

        return SuccessResponse::create(
            'get_contact_origin',
            $originData
        );
    }
}
