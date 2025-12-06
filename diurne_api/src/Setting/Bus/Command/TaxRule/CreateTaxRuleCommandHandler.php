<?php

namespace App\Setting\Bus\Command\TaxRule;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\TaxRule;
use App\Setting\Entity\TaxRuleLang;
use App\Setting\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateTaxRuleCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LanguageRepository $languageRepository
    ) {
    }

    public function __invoke(CreateTaxRuleCommand $command): TaxRuleResponse
    {
        $taxRule = new TaxRule();
        $taxRule->setTaxRate($command->taxRate);

        foreach ($command->identifications as $identificationData) {
            $language = $this->languageRepository->find($identificationData['language_id']);
            if (!$language) {
                throw new InvalidArgumentException('Invalid language');
            }

            $taxRuleLang = new TaxRuleLang();
            $taxRuleLang->setTaxRule($taxRule);
            $taxRuleLang->setLanguage($language);
            $taxRuleLang->setIdentification($identificationData['identification']);

            $this->entityManager->persist($taxRuleLang);
        }

        $this->entityManager->persist($taxRule);
        $this->entityManager->flush();

        return TaxRuleResponse::fromEntity($taxRule);
    }
}
