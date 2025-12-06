<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetOrigin;

use App\Common\Bus\Query\QueryHandler;
use App\Contact\Entity\ContactOrigin;
use App\Contact\Repository\ContactOriginRepository;

class GetContactOriginQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ContactOriginRepository $contactOriginRepository
    )
    {
    }

    /**
     * Returns an array with the ContactOrigin’s data
     */
    public function __invoke(GetContactOriginQuery $query): GetContactOriginResponse
    {
        $origins = $this->contactOriginRepository->findAll();
        $data = [];
        foreach ($origins as $origin) {
            /** @var ContactOrigin $origin */
            $data[] = [
                'id' => $origin->getId(),
                'label' => $origin->getLabel(),
            ];
        }

        return new GetContactOriginResponse($data);
    }
}
