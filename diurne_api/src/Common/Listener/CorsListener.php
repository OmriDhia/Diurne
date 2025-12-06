<?php

namespace App\Common\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class CorsListener
{
    /**
     * @return void
     */
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (!$request) {
            return;
        }

        $method = $request->getMethod();

        // Handle preflight requests
        if ('OPTIONS' === $method) {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
            $response->headers->set('Access-Control-Max-Age', '3600');
            $event->setResponse($response);

            return;
        }

        // Handle actual requests
        $response = $event->getResponse();
        if ($response) {
            $response->headers->set('Access-Control-Allow-Origin', '*');
        }
    }
}
