<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use App\Common\Assert as carpetDesignOrderAssert;
use Symfony\Component\Validator\Constraints as Assert;

class GetCarpetDesignOrdersFilterDto
{
    #[Assert\Regex(pattern: '/^\\d+$/', message: 'Designer filter must be a numeric identifier.')]
    public ?string $designer = null;

    #[Assert\Regex(pattern: '/^\\d+$/', message: 'Designer filter must be a numeric identifier.')]
    public ?string $designerid = null;

    public function getDesignerId(): ?int
    {
        $designer = $this->designer ?? $this->designerid;

        return null === $designer ? null : (int) $designer;
    }

    #[Assert\Length(max: 50, maxMessage: 'prescripteur cannot exceed {{ limit }} characters.')]
    #[carpetDesignOrderAssert\Name(message: 'Please enter a valid name.')]
    public ?string $prescripteur = null;

    public ?int $customer = null;
    public ?string $diNumber = null;

    public ?int $diId = null;

    public ?string $contremarque = null;

    public ?int $statusId = null;

    public ?bool $maquette = null;

    public ?bool $cmdAtelier = null;

    public ?int $collectionId = null;

    public ?int $modelId = null;

    public ?int $contremarqueId = null;
}
