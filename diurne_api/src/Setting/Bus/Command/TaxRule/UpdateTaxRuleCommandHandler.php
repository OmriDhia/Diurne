<?php

namespace App\Setting\Bus\Command\TaxRule;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\TaxRuleLang;
use App\Setting\Repository\LanguageRepository;
use App\Setting\Repository\TaxRuleLangRepository;
use App\Setting\Repository\TaxRuleRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateTaxRuleCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly TaxRuleRepository $taxRuleRepository,
        private readonly TaxRuleLangRepository $taxRuleLangRepository,
        private readonly LanguageRepository $languageRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(UpdateTaxRuleCommand $command): TaxRuleResponse
    {
        $taxRule = $this->taxRuleRepository->find($command->id);
        if (!$taxRule) {
            throw new InvalidArgumentException('TaxRule not found');
        }

        if (null !== $command->taxRate) {
            $taxRule->setTaxRate($command->taxRate);
        }

        if (null !== $command->identifications) {
            foreach ($command->identifications as $identificationData) {
                $language = $this->languageRepository->find($identificationData['language_id']);
                if (!$language) {
                    throw new InvalidArgumentException('Invalid language');
                }

                $taxRuleLang = $this->taxRuleLangRepository->findOneBy([
                    'taxRule' => $taxRule,
                    'language' => $language,
                ]);

                if (!$taxRuleLang) {
                    $taxRuleLang = new TaxRuleLang();
                    $taxRuleLang->setTaxRule($taxRule);
                    $taxRuleLang->setLanguage($language);
                }

                $taxRuleLang->setIdentification($identificationData['identification']);
                $this->entityManager->persist($taxRuleLang);
            }
        }

        $this->entityManager->persist($taxRule);
        $this->entityManager->flush();

        return TaxRuleResponse::fromEntity($taxRule);
    }
}
