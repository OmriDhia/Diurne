<?php

declare(strict_types=1);

namespace App\Common\Messenger;

use App\Common\Service\MailerService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email; // Make sure Email class is imported

#[AsMessageHandler]
class EmailMessageHandler
{
    public function __construct(private readonly MailerService $mailerService) {}

    public function __invoke(EmailMessage $message)
    {
        // Create the email object using the EmailMessage data
        $email = new Email();
        $email->from('no-reply@example.com')
            ->to($message->getTo())
            ->subject($message->getSubject())
            ->html($this->mailerService->renderTemplate($message->getTemplate(), $message->getContext()));

        // Attach files if they exist
        foreach ($message->getAttachments() as $attachmentPath) {
            $email->attachFromPath($attachmentPath);
        }

        // Send the email
        $this->mailerService->sendEmail($email);
    }
}
