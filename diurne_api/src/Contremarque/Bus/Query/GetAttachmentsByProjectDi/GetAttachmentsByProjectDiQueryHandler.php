<?php

namespace App\Contremarque\Bus\Query\GetAttachmentsByProjectDi;

use Exception;
use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\DiAttachmentRepository;
use App\Contremarque\Repository\ProjectDiRepository;

class GetAttachmentsByProjectDiQueryHandler implements QueryHandler
{
    public function __construct(private readonly DiAttachmentRepository $diAttachmentRepository, private readonly ProjectDiRepository $projectDiRepository)
    {
    }

    public function __invoke(GetAttachmentsByProjectDiQuery $query): GetAttachmentsByProjectDiQueryResponse
    {
        $projectDi = $this->projectDiRepository->find((int) $query->getProjectDiId());

        if (!$projectDi) {
            throw new Exception('Di not found');
        }
        $attachments = $this->diAttachmentRepository->findBy(['di' => $projectDi]);

        return new GetAttachmentsByProjectDiQueryResponse($attachments);
    }
}
