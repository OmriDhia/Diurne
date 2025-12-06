<?php

// src/Setting/Bus/Command/Quality/CreateQualityCommandHandler.php

namespace App\Setting\Bus\Command\Quality;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Setting\Entity\Quality;
use App\Setting\Entity\QualityLang;
use App\Setting\Repository\LanguageRepository;
use App\Setting\Repository\QualityRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateQualityCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly QualityRepository $qualityRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly LanguageRepository $languageRepository
    ) {}

    public function __invoke(CreateQualityCommand $command): QualityResponse
    {
        // Check if a quality with the same name already exists
        $existingQuality = $this->qualityRepository->findOneBy(['name' => $command->name]);
        if ($existingQuality) {
            throw new  ValidationException(['Quality with this name already exists.']);
        }

        // Create a new Quality entity and set its properties

        $quality = new Quality();
        $quality->setName($command->name);
        $quality->setWeight($command->weight);
        $quality->setVelvetStandard($command->velvetStandard);

        foreach ($command->description as $descriptionData) {
            $qualityLang = new QualityLang();
            $qualityLang->setQuality($quality);
            $language = $this->languageRepository->find((int) $descriptionData['id_lang']);
            $qualityLang->setLanguage($language); // Assuming Language entity is set here
            $qualityLang->setDescription($descriptionData['text']);
            $this->entityManager->persist($qualityLang);
        }

        $this->entityManager->persist($quality);
        $this->entityManager->flush();
        $this->entityManager->refresh($quality);

        return new QualityResponse($quality);
    }
}
