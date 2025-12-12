<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\RN\DeleteRN;

use App\Common\Bus\Command\CommandHandler;
use App\MobileAppApi\Repository\RNRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsMessageHandler]
final class DeleteRNHandler implements CommandHandler
{
    public function __construct(
        private readonly RNRepository $repository
    ) {
    }

    public function __invoke(DeleteRNCommand $command): void
    {
        $rn = $this->repository->find($command->id);

        if (!$rn) {
            throw new NotFoundHttpException('RN not found');
        }

        $this->repository->remove($rn, true);
    }
}
