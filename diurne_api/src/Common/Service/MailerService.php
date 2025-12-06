<?php

declare(strict_types=1);

namespace App\Common\Service;

use Throwable;
use RuntimeException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailerService
{
    public function __construct(private readonly MailerInterface $mailer, private readonly Environment $twig)
    {
    }

    /**
     * Renders the Twig template.
     *
     * @param string $template Twig template for the email body.
     * @param array  $context  Variables to pass to the Twig template.
     * @return string The rendered template.
     */
    public function renderTemplate(string $template, array $context): string
    {
        // Ensure the rendered template doesn't fail silently.
        try {
            return $this->twig->render($template, $context);
        } catch (Throwable $e) {
            throw new RuntimeException(sprintf('Failed to render email template "%s": %s', $template, $e->getMessage()), 0, $e);
        }
    }

    /**
     * Sends an email using the provided email object.
     *
     * @param Email $email The email object to send.
     */
    public function sendEmail(Email $email): void
    {
        // Send the email and handle potential exceptions.
        try {
            $this->mailer->send($email);
        } catch (Throwable $e) {
            throw new RuntimeException(sprintf('Failed to send email to "%s": %s', $email->getTo(), $e->getMessage()), 0, $e);
        }
    }
}
