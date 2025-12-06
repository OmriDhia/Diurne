<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\AttachmentType;

use App\Common\Bus\Query\CacheableQueryHandlerTrait;
use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\AttachmentTypeRepository;

class GetAllAttachmentTypesQueryHandler implements QueryHandler
{
    use CacheableQueryHandlerTrait;
    public function __construct(private readonly AttachmentTypeRepository $attachmentTypeRepository) {}

    public function __invoke(GetAllAttachmentTypesQuery $query): AttachmentTypeQueryResponse
    {
        // Fetch all attachment types from the repository
        $allAttachmentTypes = $this->attachmentTypeRepository->findAll();

        // Return response with attachment type data
        return new AttachmentTypeQueryResponse($allAttachmentTypes);
    }
}
