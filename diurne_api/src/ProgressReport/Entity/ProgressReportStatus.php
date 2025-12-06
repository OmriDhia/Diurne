<?php
declare(strict_types=1);

namespace App\ProgressReport\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ProgressReportStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'text')]
    private string $status;

    public function getId(): int
    {
        return $this->id;
    }


    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
        ];
    }


}
