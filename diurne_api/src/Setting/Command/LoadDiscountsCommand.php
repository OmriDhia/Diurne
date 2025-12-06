<?php

namespace App\Setting\Command;

use Exception;
use App\Setting\Entity\DiscountRule;
use App\Setting\Entity\DiscountRuleLang;
use App\Setting\Entity\Language;
use App\Setting\Repository\DiscountRuleLangRepository;
use App\Setting\Repository\DiscountRuleRepository;
use App\Setting\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:load-discounts',
    description: 'Load discount rules into the database'
)]
class LoadDiscountsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DiscountRuleRepository $discountRuleRepository,
        private readonly LanguageRepository $languageRepository,
        private readonly DiscountRuleLangRepository $discountRuleLangRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->loadDiscounts();
            $io->success('Discount rules loaded successfully.');
        } catch (Exception $e) {
            $io->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function loadDiscounts(): void
    {
        $manager = $this->entityManager;

        // Define discount rules and their labels
        $discountRules = [
            [
                'discount' => 0,
                'labels' => [
                    ['name' => 'Français', 'label' => 'Tarif public'],
                    ['name' => 'Anglais', 'label' => 'Public tarif'],
                    ['name' => 'Allemand', 'label' => 'Öffentlicher Tarif'],
                ],
            ],
            [
                'discount' => 20,
                'labels' => [
                    ['name' => 'Français', 'label' => 'Tarif Professionnel'],
                    ['name' => 'Anglais', 'label' => 'Professional Rate'],
                    ['name' => 'Allemand', 'label' => 'Professioneller Tarif'],
                ],
            ],
            [
                'discount' => 30,
                'labels' => [
                    ['name' => 'Français', 'label' => 'Tarif Professionnel - Galerie'],
                    ['name' => 'Anglais', 'label' => 'Professional Rate - Gallery'],
                    ['name' => 'Allemand', 'label' => 'Professioneller Preis – Galerie'],
                ],
            ],
            [
                'discount' => 40,
                'labels' => [
                    ['name' => 'Français', 'label' => 'Tarif Professionnel - Premium'],
                    ['name' => 'Anglais', 'label' => 'Professional Rate - Premium'],
                    ['name' => 'Allemand', 'label' => 'Professioneller Preis – Premium'],
                ],
            ],
        ];

        foreach ($discountRules as $ruleData) {
            // Check if the discount rule already exists
            $existingDiscount = $this->discountRuleRepository->findOneBy(['discount' => $ruleData['discount']]);

            if (!$existingDiscount) {
                $discount = new DiscountRule();
                $discount->setDiscount($ruleData['discount']);
                $manager->persist($discount);
                $manager->flush();
            } else {
                $discount = $existingDiscount;
            }

            foreach ($ruleData['labels'] as $labelData) {
                // Find the language by name
                $language = $this->languageRepository->findOneBy(['name' => $labelData['name']]);
                if (!$language) {
                    throw new Exception(sprintf('Language %s not found', $labelData['name']));
                }

                // Check if the discount rule label already exists
                $existingLabel = $this->discountRuleLangRepository->findOneBy([
                    'discountRule' => $discount,
                    'langId' => $language->getId(),
                ]);

                if (!$existingLabel) {
                    $discountLang = new DiscountRuleLang();
                    $discountLang->setLabel($labelData['label']);
                    $discountLang->setLangId($language->getId());
                    $discountLang->setDiscountRule($discount);
                    $manager->persist($discountLang);
                    $discount->addDiscountRuleLang($discountLang);
                }
            }

            $manager->persist($discount);
            $manager->flush();
        }
    }
}
