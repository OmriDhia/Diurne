<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\UpdateWorkshopImage;

use App\Common\Bus\Command\Command;

class UpdateWorkshopImageCommand implements Command
{
    /**
     * @param int $id
     * @param string $fileName
     * @param int $idImageType
     * @param string $format
     * @param int $locationId
     * @param int $workshopOrderId
     * @param int $attachmentId
     */
    public function __construct(
        public readonly int    $id,
        public readonly string $fileName,
        public readonly int    $idImageType,
        public readonly string $format,
        public readonly int    $locationId,
        public readonly int    $workshopOrderId,
        public readonly int    $attachmentId,
    )
    {
    }
}