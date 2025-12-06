<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\DeleteContact\DeleteContactCommand;
use App\Contact\Entity\Contact;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DeleteContactController extends CommandQueryController
{
    #[Route('/api/contact/{contactId}/delete', name: 'contact_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Contact delete',
        content: new Model(type: Contact::class)
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        $contactId
    ): JsonResponse {
        if (!$this->isGranted('delete', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $deleteContactCommand = new DeleteContactCommand(
            (int) $contactId,
        );
        $contactResponse = $this->handle($deleteContactCommand);

        return SuccessResponse::create(
            'contact_delete',
            $contactResponse->toArray()
        );
    }
}
