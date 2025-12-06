<?php

namespace App\Contremarque\Bus\Query\GetCarpetDesignOrderAttachments;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\CarpetDesignOrderAttachmentRepository;

class GetCarpetDesignOrderAttachmentsQueryHandler implements QueryHandler
{
    public function __construct(private readonly CarpetDesignOrderAttachmentRepository $repository)
    {
    }

    public function __invoke(GetCarpetDesignOrderAttachmentsQuery $query): GetCarpetDesignOrderAttachmentsQueryResponse
    {
        $attachments = $this->repository->findBy(['carpetDesignOrder' => $query->getCarpetDesignOrderId()]);

        return new GetCarpetDesignOrderAttachmentsQueryResponse($attachments);
    }
}
