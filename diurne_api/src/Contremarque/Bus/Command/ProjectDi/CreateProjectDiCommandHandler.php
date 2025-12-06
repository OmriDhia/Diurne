<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\ProjectDi;

use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Contremarque\Entity\ProjectDi;
use App\Contremarque\Repository\ContremarqueRepository;
use App\Contremarque\Repository\ProjectDiRepository;
use App\Contremarque\Repository\UnitOfMeasurementRepository;
use App\Event\Entity\Event;
use App\Event\Repository\EventNomenclatureRepository;
use App\Event\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateProjectDiCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProjectDiRepository $projectDiRepository,
        private readonly UnitOfMeasurementRepository $unitOfMeasurementRepository,
        private readonly ContremarqueRepository $contremarqueRepository,
        private readonly EventRepository $eventRepository,
        private readonly EventNomenclatureRepository $eventNomenclatureRepository
    ) {
    }

    public function __invoke(CreateProjectDiCommand $command): ProjectDi
    {
        if (empty($command->contremarque_id)) {
            throw new ValidationException(['Contremarque is required cannot create DI without contremarque']);
        }
        $projectDi = new ProjectDi();
        $demande_number = $this->projectDiRepository->generateProjectNumber();
        $projectDi->setDemandeNumber($demande_number);
        $projectDi->setFormat($command->format);
        $projectDi->setDeadline($command->deadline);
        $projectDi->setTransmittedToStudio($command->transmitted_to_studio);
        $projectDi->setTransmitionDate($command->transmition_date);
        if (null !== $command->unit_id) {
            $unit = $this->unitOfMeasurementRepository->find((int) $command->unit_id);
            $projectDi->setUnit($unit);
        }
        $contremarque = $this->contremarqueRepository->find((int) $command->contremarque_id);

        if (null === $contremarque) {
            throw new ValidationException(['Contremarque not found']);
        }

        $customer = $contremarque->getCustomer();

        if (null === $customer) {
            throw new ValidationException(['Customer not found']);
        }

        $projectDi->setContremarque($contremarque);
        $projectDi->setCreatedAt(new DateTimeImmutable());
        $projectDi->setTransmittedToStudio(false);

        $this->entityManager->persist($projectDi);
        $this->entityManager->flush();
        $nomenclature = $this->eventNomenclatureRepository->findBySubject('Nouvelle DI Projet');

        $event = new Event();
        $event->setNomenclature($nomenclature);
        $event->setCustomer($customer);
        $event->setContremarque($contremarque);
        $event->setEventDate(new DateTimeImmutable());
        $event->setCommentaire('N° de la Demande: '.$demande_number);
        $this->eventRepository->persist($event);
        $this->eventRepository->flush();

        return $projectDi;
    }
}
