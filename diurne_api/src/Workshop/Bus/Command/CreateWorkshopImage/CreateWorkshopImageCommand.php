<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateWorkshopImage;

use App\Common\Bus\Command\Command;

class CreateWorkshopImageCommand implements Command
{
    /**
     * @param string $fileName
     * @param int $idImageType
     * @param string $format
     * @param int $locationId
     * @param int $workshopOrderId
     * @param int $attachmentId
     * @param string|null $createdAt
     * @param string|null $updatedAt
     */
    public function __construct(
        public readonly string  $fileName,
        public readonly int     $idImageType,
        public readonly string  $format,
        public readonly int     $locationId,
        public readonly int     $workshopOrderId,
        public readonly int     $attachmentId,
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null
    )
    {
    }
}