<?php

declare(strict_types=1);

namespace App\Contremarque\Service\ProjectDi;

use App\Contremarque\Entity\ProjectDi;
use App\Contremarque\Entity\DiAttachment;
use App\Contremarque\Entity\Attachment;
use App\Contremarque\Repository\ProjectDiRepository;
use App\Contremarque\Service\CarpetDesignOrder\CarpetDesignOrderCloner;
use DateTimeImmutable;

class ProjectDiCloner
{
    public function __construct(
        private readonly ProjectDiRepository $projectDiRepository,
        private readonly CarpetDesignOrderCloner $carpetDesignOrderCloner,
    ) {
    }

    public function clone(ProjectDi $original): ProjectDi
    {
        $newProject = clone $original;
        $newProject->setDemandeNumber(
            $this->projectDiRepository->generateProjectNumber()
        );
        $newProject->setCreatedAt(new DateTimeImmutable());

        foreach ($original->getAttachments() as $attachment) {
            $newProject->addAttachment(clone $attachment);
        }

        foreach ($original->getDiAttachments() as $diAttachment) {
            $clonedDiAttachment = clone $diAttachment;
            if ($diAttachment->getAttachment() instanceof Attachment) {
                $clonedDiAttachment->setAttachment(clone $diAttachment->getAttachment());
            }
            $clonedDiAttachment->setDi($newProject);
            $newProject->addDiAttachment($clonedDiAttachment);
        }

        foreach ($original->getCarpetDesignOrders() as $order) {
            $clonedOrder = $this->carpetDesignOrderCloner->clone($order);
            $clonedOrder->setProjectDi($newProject);
            $newProject->addCarpetDesignOrder($clonedOrder);
        }

        return $newProject;
    }
}
