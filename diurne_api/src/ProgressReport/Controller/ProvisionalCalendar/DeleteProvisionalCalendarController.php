<?php

namespace App\ProgressReport\Controller\ProvisionalCalendar;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\ProgressReport\Bus\Command\ProvisionalCalendar\DeleteProvisionalCalendar\DeleteProvisionalCalendarCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Delete a provisional calendar entry.
 */
class DeleteProvisionalCalendarController extends CommandQueryController
{
    #[Route('/api/provisionalCalendar/{id}', name: 'provisional_calendar_delete', methods: ['DELETE'])]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'progressReport')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $this->handle(new DeleteProvisionalCalendarCommand($id));
        return SuccessResponse::create('provisional_calendar_deleted', null);
    }
}


