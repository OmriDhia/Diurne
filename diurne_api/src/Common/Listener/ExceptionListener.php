<?php

namespace App\Common\Listener;

use App\Common\Exception\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationException) {
            $response = new JsonResponse([
                'error' => 'Validation Failed',
                'violations' => $exception->getViolations(),
            ], 400);

            $event->setResponse($response);
        }
    }
}
