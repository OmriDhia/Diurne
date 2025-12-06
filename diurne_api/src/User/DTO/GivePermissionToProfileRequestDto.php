<?php

declare(strict_types=1);

namespace App\User\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class GivePermissionToProfileRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type(
            type: 'integer',
            message: 'The value {{ value }} is not a valid {{ type }}.',
        )]
        public int $permissionId,
        #[Assert\NotBlank]
        #[Assert\Type(
            type: 'integer',
            message: 'The value {{ value }} is not a valid {{ type }}.',
        )]
        public int $profileId,
    ) {
    }
}
