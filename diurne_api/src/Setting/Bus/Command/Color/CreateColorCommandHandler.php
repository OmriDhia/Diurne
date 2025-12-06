<?php

namespace App\Setting\Bus\Command\Color;

use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\Color;
use App\Setting\Repository\ColorRepository;

class CreateColorCommandHandler implements CommandHandler
{
    public function __construct(private readonly ColorRepository $colorRepository)
    {
    }

    public function __invoke(CreateColorCommand $command): ColorResponse
    {
        $color = new Color();
        $color->setReference($command->getReference());
        $color->setHexCode($command->getHexCode());

        $this->colorRepository->save($color, true);

        return new ColorResponse($color);
    }
}
