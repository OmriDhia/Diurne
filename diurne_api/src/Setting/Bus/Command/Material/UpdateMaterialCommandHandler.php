<?php

namespace App\Setting\Bus\Command\Material;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\MaterialLang;
use App\Setting\Repository\LanguageRepository;
use App\Setting\Repository\MaterialLangRepository;
use App\Setting\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateMaterialCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly MaterialRepository $materialRepository,
        private readonly MaterialLangRepository $materialLangRepository,
        private readonly LanguageRepository $languageRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(UpdateMaterialCommand $command): MaterialResponse
    {
        $material = $this->materialRepository->find($command->id);

        if (!$material) {
            throw new InvalidArgumentException('Material not found');
        }

        if (null !== $command->reference) {
            $material->setReference($command->reference);
        }

        if (null !== $command->descriptions) {
            foreach ($command->descriptions as $descriptionData) {
                $language = $this->languageRepository->find((int) $descriptionData['language_id']);
                if (!$language) {
                    throw new InvalidArgumentException('Invalid language code');
                }

                $materialLang = $this->materialLangRepository->findOneBy([
                    'material' => $material,
                    'language' => $language,
                ]);

                if (!$materialLang) {
                    $materialLang = new MaterialLang();
                    $materialLang->setMaterial($material);
                    $materialLang->setLanguage($language);
                }

                $materialLang->setLabel($descriptionData['label']);
                $this->entityManager->persist($materialLang);
            }
        }

        $this->entityManager->persist($material);
        $this->entityManager->flush();

        return new MaterialResponse($material);
    }
}
