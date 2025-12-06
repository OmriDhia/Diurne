<?php

namespace App\Workshop\Bus\Command\CreateCarpet;

use App\Common\Bus\Command\Command;

class CreateCarpetCommand implements Command
{
    /**
     * @param int $manufacturerId
     */
    public function __construct(
        public readonly int  $manufacturerId,
        public readonly ?int $imageCommandId = null
    )
    {
    }
}