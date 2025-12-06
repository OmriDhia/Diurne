<?php

namespace App\Setting\Bus\Command\Color;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\ColorRepository;
use Doctrine\ORM\Exception\ORMException;

class UpdateColorCommandHandler implements CommandHandler
{
    public function __construct(private readonly ColorRepository $colorRepository)
    {
    }

    /**
     * @throws ORMException
     */
    public function __invoke(UpdateColorCommand $command): ColorResponse
    {
        $color = $this->colorRepository->find($command->getId());

        if (null === $color) {
            throw new ResourceNotFoundException();
        }
        $color->setReference($command->getReference());
        $color->setHexCode($command->getHexCode());

        $this->colorRepository->save($color, true);

        return new ColorResponse($color);
    }
}
