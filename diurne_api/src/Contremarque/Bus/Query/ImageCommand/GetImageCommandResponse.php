<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\ImageCommand;

use App\Common\Bus\Query\QueryResponse;

class GetImageCommandResponse implements QueryResponse
{
    /**
     * @param array $imageCommands
     * @param int $total
     * @param int $pages
     */
    public function __construct(
        public array $imageCommands,
        public int   $total,
        public int   $pages
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'data' => $this->imageCommands,
            'meta' => [
                'total' => $this->total,
                'pages' => $this->pages
            ]
        ];
    }
}