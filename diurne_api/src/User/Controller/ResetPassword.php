<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Response\SuccessResponse;
use App\User\Entity\User;
use App\User\Manager\PasswordTokenManager;
use CoopTilleuls\ForgotPasswordBundle\Manager\ForgotPasswordManager;
use CoopTilleuls\ForgotPasswordBundle\Provider\ProviderInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;

final readonly class ResetPassword
{
    public function __construct(private ForgotPasswordManager $forgotPasswordManager, private PasswordTokenManager $passwordTokenManager) {}

    #[Route('/forgot-password', name: 'reset_password', methods: ['POST'])]
    public function __invoke($propertyName, $value, ProviderInterface $provider, MailerInterface $mailer)
    {
        $this->forgotPasswordManager->resetPassword($propertyName, $value, $provider);
        $context = [$propertyName => $value];
        $user = $provider->getManager()->findOneBy(User::class, $context);
        $token = $this->passwordTokenManager->findOneByUser($user, $provider);
        $this->sendResetEmail($mailer, $user, $token);

        return SuccessResponse::create(
            'reset_password',
            [
                'user_id' => $token->getUser()->getId(),
                'email' => $value,
                'token' => $token->getToken(),
            ],
            'Password reset email was sent successfully.'

        );
    }

    private function sendResetEmail(MailerInterface $mailer, User $user, $token): Response
    {
        $email = (new TemplatedEmail())
            ->from('noreplay@diurne.com')
            ->to($user->getEmail()) // Primary recipient
            ->subject('Reset Your Password')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'user' => $user,
                'app_name' => 'Diurne',
                'expireAt' => $token->getExpiresAt()->format('Y-m-d H:i:s'),
                'reset_url' => 'https://diurne.webntricks.com/pass-reset/' . $token->getToken(), // Get TTL from security config
            ]);

        $mailer->send($email);

        return new Response('Email was sent successfully.');
    }
}
