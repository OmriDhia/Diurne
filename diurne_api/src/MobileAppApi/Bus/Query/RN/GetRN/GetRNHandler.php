<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\RN\GetRN;

use App\Common\Bus\Query\QueryHandler;
use App\MobileAppApi\Entity\RN;
use App\MobileAppApi\Repository\RNRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsMessageHandler]
final class GetRNHandler implements QueryHandler
{
    public function __construct(
        private readonly RNRepository $repository
    ) {
    }

    public function __invoke(GetRNQuery $query): RN
    {
        $rn = $this->repository->find($query->id);

        if (!$rn) {
            throw new NotFoundHttpException('RN not found');
        }

        return $rn;
    }
}
