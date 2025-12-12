<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\User\GetUserMobileApp;

use App\Common\Bus\Query\Query;
use Symfony\Component\Validator\Constraints as Assert;

final class GetUserMobileAppQuery implements Query
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly int $id
    ) {}
}
