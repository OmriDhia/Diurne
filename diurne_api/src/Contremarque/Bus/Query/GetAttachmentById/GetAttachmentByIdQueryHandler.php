<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetAttachmentById;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\AttachmentRepository;

final readonly class GetAttachmentByIdQueryHandler implements QueryHandler
{
    public function __construct(private AttachmentRepository $attachmentRepository)
    {
    }

    public function __invoke(GetAttachmentByIdQuery $query): GetAttachmentByIdResponse
    {
        $attachment = $this->attachmentRepository->find($query->getId());

        return new GetAttachmentByIdResponse($attachment);
    }
}
