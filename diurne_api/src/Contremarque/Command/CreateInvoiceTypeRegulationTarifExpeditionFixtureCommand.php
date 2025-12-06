<?php

declare(strict_types=1);

namespace App\Contremarque\Command;

use App\Contremarque\Entity\InvoiceType;
use App\Contremarque\Entity\Regulation;
use App\Contremarque\Entity\TarifExpedition;
use App\Contremarque\Repository\InvoiceTypeRepository;
use App\Contremarque\Repository\RegulationRepository;
use App\Contremarque\Repository\TarifExpeditionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-invoice-type-regulation-tarif-expedition',
    description: 'Create InvoiceType, Regulation and TarifExpedition fixtures'
)]
class CreateInvoiceTypeRegulationTarifExpeditionFixtureCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface    $entityManager,
        private readonly InvoiceTypeRepository     $invoiceTypeRepository,
        private readonly RegulationRepository      $regulationRepository,
        private readonly TarifExpeditionRepository $tarifExpeditionRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $invoiceTypes = [
            'Acompte',
            'Solde',
            'Avoir',
            'Pro Forma',
        ];

        foreach ($invoiceTypes as $name) {
            if (null === $this->invoiceTypeRepository->findOneBy(['name' => $name])) {
                $entity = new InvoiceType();
                $entity->setName($name);
                $this->entityManager->persist($entity);
            }
        }

        $regulations = [
            'Carte Bleue',
            'Chèque',
            'Virement',
            'Überweisung',
            'Bank Transfer',
            'Compensation',
        ];

        foreach ($regulations as $name) {
            if (null === $this->regulationRepository->findOneBy(['name' => $name])) {
                $entity = new Regulation();
                $entity->setName($name);
                $this->entityManager->persist($entity);
            }
        }

        $tarifExpeditions = [
            'Lieferung frei Haus',
            'Facturer le compte de l\'expediteur',
            'Facturer le destinataire',
            'C et F',
            'Franco domicile dédouané droits et taxes inclus',
            'Goods without taxes for Export - DDP - International Customs Nomenclature 5701104000',
            'Goods without taxes for Export - DDP -  International Customs Nomenclature 5701901010',
        ];

        foreach ($tarifExpeditions as $name) {
            if (null === $this->tarifExpeditionRepository->findOneBy(['name' => $name])) {
                $entity = new TarifExpedition();
                $entity->setName($name);
                $this->entityManager->persist($entity);
            }
        }

        $this->entityManager->flush();

        $io->success('Invoice types, regulations and tarif expeditions created successfully.');

        return Command::SUCCESS;
    }
}

