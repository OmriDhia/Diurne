<?php

// src/Setting/Bus/Command/CarpetCollection/CreateCarpetCollectionLangCommandHandler.php

namespace App\Setting\Bus\Command\CarpetCollection;

use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\CarpetCollectionLang;
use App\Setting\Repository\CarpetCollectionRepository;
use App\Setting\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateCarpetCollectionLangCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LanguageRepository $languageRepository,
        private readonly CarpetCollectionRepository $carpetCollectionRepository
    ) {
    }

    public function __invoke(CreateCarpetCollectionLangCommand $command): CarpetCollectionLangResponse
    {
        $carpetCollectionLang = new CarpetCollectionLang();
        $carpetCollectionLang
            ->setDescription($command->getDescription())
            ->setCarpetCollection($this->carpetCollectionRepository->find((int) $command->getCarpetCollectionId()))
            ->setLanguage($this->languageRepository->find($command->getLanguageId()));

        $this->entityManager->persist($carpetCollectionLang);
        $this->entityManager->flush();

        return new CarpetCollectionLangResponse($carpetCollectionLang);
    }
}
