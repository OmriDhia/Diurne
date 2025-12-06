<?php

declare(strict_types=1);

namespace App\Event\Bus\Command\Event;

use App\Common\Bus\Command\Command;

class CreateEventConfigurationCommand implements Command
{
    private string $subject;
    private bool $is_automatic;
    private ?int $automatic_followup_delay = null;

    public function __construct(string $subject, bool $is_automatic)
    {
        $this->setSubject($subject);
        $this->setIsAutomatic($is_automatic);
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): CreateEventConfigurationCommand
    {
        $this->subject = $subject;

        return $this;
    }

    public function isIsAutomatic(): bool
    {
        return $this->is_automatic;
    }

    public function setIsAutomatic(bool $is_automatic): CreateEventConfigurationCommand
    {
        $this->is_automatic = $is_automatic;

        return $this;
    }

    public function getAutomaticFollowupDelay(): ?int
    {
        return $this->automatic_followup_delay;
    }

    public function setAutomaticFollowupDelay(?int $automatic_followup_delay): CreateEventConfigurationCommand
    {
        $this->automatic_followup_delay = $automatic_followup_delay;

        return $this;
    }
}
