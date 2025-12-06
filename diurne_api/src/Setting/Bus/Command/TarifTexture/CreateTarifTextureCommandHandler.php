<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\TarifTexture;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Setting\Repository\TarifTextureRepository;
use App\Setting\Entity\TarifTexture;

class CreateTarifTextureCommandHandler implements CommandHandler
{
    public function __construct(private readonly TarifTextureRepository $tarifTextureRepository)
    {
    }

    public function __invoke(CreateTarifTextureCommand $command): TarifTextureResponse
    {
        $existing = $this->tarifTextureRepository->findOneByYear($command->year);
        if ($existing) {
            throw new ValidationException(['There is another tarif texture with the same year.']);
        }

        $entity = new TarifTexture();
        $entity->setYear($command->year);

        $this->tarifTextureRepository->persist($entity);
        $this->tarifTextureRepository->flush();

        return new TarifTextureResponse($entity);
    }
}

