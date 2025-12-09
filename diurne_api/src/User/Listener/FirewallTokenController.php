<?php

namespace App\User\Listener;

use Exception;
use App\User\Repository\UserRepository;
use App\User\Repository\UserMobileAppRepository;
use App\User\Security\AuthService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class FirewallTokenController
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly Security $security,
        private readonly UserRepository $userRepository,
        private readonly UserMobileAppRepository $mobileUserRepository
    ) {}

    /**
     * @return void
     */
    public function onKernelController(ControllerEvent $event)
    {
        if (!$event->isMainRequest()) {
            // don't do anything if it's not the main request
            return;
        }
        $request = $event->getRequest();
        $routeName = $request->attributes->get('_route');

        if ($routeName && in_array($routeName, $this->authService->excludedRoutes())) {
            return;
        }

        if (!$this->authService->isAuthenticated()) {
            $this->handleInvalidToken($event, 'Invalid JWT Token', 401);

            return;
        }

        $token = $this->authService->getToken();

        if (!$token) {
            $this->handleInvalidToken($event, 'Invalid JWT Token', 401);

            return;
        }

        try {
            $decodedToken = $this->authService->getDecodedAuthToken();
            $email = $decodedToken['email'];
            $session = $request->getSession();

            if ($request->query->get('clearSession')) {
                $session->clear();
            }

            if (!$session->get('user')) {
                $user = $this->userRepository->findByEmail($email);
                if (!$user) {
                    $user = $this->mobileUserRepository->findOneBy(['email' => $email]);
                }
                $session->set('user', $user);
                if (!$user instanceof UserInterface) {
                    $this->handleInvalidToken($event, 'Unauthorized to access this content', 401);

                    return;
                }
            }
        } catch (Exception) {
            $this->handleInvalidToken($event, 'Invalid JWT Token', 401);

            return;
        }
    }

    private function handleInvalidToken($event, $message, $errorCode): void
    {
        $event->setController(fn() => new JsonResponse(['code' => 401, 'message' => $message], $errorCode));
    }
}
