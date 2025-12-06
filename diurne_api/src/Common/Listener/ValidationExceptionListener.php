<?php

namespace App\Common\Listener;

use App\Common\Exception\ValidationException;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpFoundation\Request;

class ValidationExceptionListener
{
    #[AsEventListener]
    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();

        if (in_array($request->getMethod(), [Request::METHOD_POST, Request::METHOD_PUT, 'BATCH'], true)) {
            ValidationException::throwIfNeeded();
        }
    }
}
