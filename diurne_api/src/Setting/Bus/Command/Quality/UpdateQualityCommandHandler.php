<?php

namespace App\Setting\Bus\Command\Quality;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\QualityLang;
use App\Setting\Repository\LanguageRepository;
use App\Setting\Repository\QualityLangRepository;
use App\Setting\Repository\QualityRepository;

class UpdateQualityCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly QualityRepository $qualityRepository,
        private readonly QualityLangRepository $qualityLangRepository,
        private readonly LanguageRepository $languageRepository
    ) {
    }

    public function __invoke(UpdateQualityCommand $command): QualityResponse
    {
        $quality = $this->qualityRepository->find($command->id);

        if (!$quality) {
            throw new InvalidArgumentException('Quality not found');
        }

        if (null !== $command->name) {
            $quality->setName($command->name);
        }

        if (null !== $command->weight) {
            $quality->setWeight($command->weight);
        }

        if (null !== $command->velvet_standard) {
            $quality->setVelvetStandard($command->velvet_standard);
        }

        foreach ($command->description as $descriptionData) {
            $language = $this->languageRepository->find((int) $descriptionData['id_lang']);
            if (!$language) {
                throw new InvalidArgumentException('Invalid language code');
            }

            $qualityLang = $this->qualityLangRepository->findOneBy([
                'quality' => $quality,
                'language' => $language,
            ]);

            if (!$qualityLang) {
                $qualityLang = new QualityLang();
                $qualityLang->setQuality($quality);
                $qualityLang->setLanguage($language);
            }

            $qualityLang->setDescription($descriptionData['text']);
            $this->qualityLangRepository->save($qualityLang);
        }

        $this->qualityRepository->save($quality);

        return new QualityResponse($quality);
    }
}
