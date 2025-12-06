<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetType;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\Contremarque\Entity\CarpetType;
use App\Contremarque\Repository\CarpetTypeRepository;

class CreateCarpetTypeCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CarpetTypeRepository $carpetTypeRepository,
    ) {
    }

    public function __invoke(CreateCarpetTypeCommand $command): CarpetTypeResponse
    {
        $carpetType = $this->carpetTypeRepository->findOneByName($command->getName());

        if ($carpetType instanceof CarpetType) {
            throw new DuplicateValidationResourceException();
        }
        /**
         * @var CarpetType
         */
        $carpetType = $this->carpetTypeRepository->create(
            [
                'name' => $command->getName(),
            ]
        );

        return new CarpetTypeResponse(
            $carpetType->getId(),
            $carpetType->getName()
        );
    }
}
