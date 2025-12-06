<?php

declare(strict_types=1);

namespace App\User\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class DeletePermissionRequestDto
{
    /**
     * Constructor for the class.
     *
     * @param string $name the name for the permission
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(
            min: 3, max: 60,
        )]
        public string $name,
    ) {
    }
}
