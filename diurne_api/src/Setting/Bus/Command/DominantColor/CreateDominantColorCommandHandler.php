<?php

namespace App\Setting\Bus\Command\DominantColor;

use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\DominantColor;
use App\Setting\Repository\DominantColorRepository;

class CreateDominantColorCommandHandler implements CommandHandler
{
    public function __construct(private readonly DominantColorRepository $dominantColorRepository)
    {
    }

    public function __invoke(CreateDominantColorCommand $command): DominantColorResponse
    {
        $dominantColor = new DominantColor();
        $dominantColor->setName($command->getName());
        $dominantColor->setHexCode($command->getHexCode());

        $this->dominantColorRepository->save($dominantColor, true);

        return new DominantColorResponse($dominantColor);
    }
}
