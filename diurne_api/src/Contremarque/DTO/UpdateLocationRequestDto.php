<?php

// src/Contremarque/DTO/UpdateLocationRequestDto.php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateLocationRequestDto
{
    /**
     * @Assert\Type(type="integer")
     */
    public ?int $carpetTypeId = null;

    /**
     * @Assert\Type(type="string")
     */
    public ?string $description = null;

    /**
     * @Assert\Type(type="boolean")
     */
    public ?bool $quoteProcessed = null;

    /**
     * @Assert\DateTime()
     */
    public ?string $quoteProcessingDate = null;

    /**
     * @Assert\Type(type="numeric")
     */
    public ?string $priceMin = null;

    /**
     * @Assert\Type(type="numeric")
     */
    public ?string $priceMax = null;

    /**
     * @Assert\DateTime()
     */
    public ?string $updatedAt = null;

    /**
     * @Assert\Type(type="integer")
     */
    public ?int $contremarqueId = null;

    // Add more properties as needed

    // Getter and Setter methods can be added if necessary
}
