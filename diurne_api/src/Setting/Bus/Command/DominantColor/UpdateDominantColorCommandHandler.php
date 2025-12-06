<?php

namespace App\Setting\Bus\Command\DominantColor;

use App\Common\Exception\ResourceNotFoundException;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Repository\DominantColorRepository;

class UpdateDominantColorCommandHandler implements CommandHandler
{
    public function __construct(private readonly DominantColorRepository $dominantColorRepository)
    {
    }

    public function __invoke(UpdateDominantColorCommand $command): DominantColorResponse
    {
        $color = $this->dominantColorRepository->find($command->getId());

        if (!$color) {
            throw new ResourceNotFoundException();
        }

        // Update only non-null fields
        if ($command->getName()) {
            $color->setName($command->getName());
        }

        if ($command->getHexCode()) {
            $color->setHexCode($command->getHexCode());
        }

        $this->dominantColorRepository->save($color, true);

        return new DominantColorResponse($color);
    }
}
