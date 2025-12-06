<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\ExportQuoteDocx;

use App\Common\Bus\Command\Command;

/**
 * Command to export a quote as a DOCX file.
 *
 * This command encapsulates the data required to export a specific quote
 * into a Word document, including the quote ID and the user ID for permission checks.
 */
class ExportQuoteDocxCommand implements Command
{
    public function __construct(private readonly int $quoteId, private readonly int $userId)
    {
    }

    public function getQuoteId(): int
    {
        return $this->quoteId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
