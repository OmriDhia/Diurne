<?php

declare(strict_types=1);

namespace App\Common\Messenger;

class EmailMessage
{
    private array $attachments = [];
    public function __construct(private readonly string $to, private readonly string $subject, private readonly string $template, private readonly array $context)
    {
    }




    public function addAttachment(string $attachmentPath): void

    {

        $this->attachments[] = $attachmentPath;
    }



    public function getAttachments(): array

    {

        return $this->attachments;
    }
    public function getTo(): string
    {
        return $this->to;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
