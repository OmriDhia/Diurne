<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\Attachment;
use App\Contremarque\Repository\AttachmentRepository;
use App\Contremarque\Repository\CarpetDesignOrderAttachmentRepository;
use App\Contremarque\Repository\DiAttachmentRepository;
use App\Contremarque\Repository\ImageAttachmentRepository;
use App\Contremarque\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMAttachmentRepository extends DoctrineORMRepository implements AttachmentRepository
{
    protected const ENTITY_CLASS = Attachment::class;
    protected const ALIAS = 'attachment';

    /**
     * DoctrineORMAttachmentRepository constructor.
     */
    public function __construct(EntityManagerInterface                        $manager,
                                private readonly DiAttachmentRepository                $diAttachmentRepository,
                                private readonly CarpetDesignOrderAttachmentRepository $carpetDesignOrderAttachmentRepository,
                                private readonly ImageAttachmentRepository             $imageAttachmentRepository,
                                private readonly ImageRepository                       $imageRepository,
    )
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @return void
     */
    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    /**
     * @return void
     */
    public function update($entity, array $data)
    {
        // TODO: Implement update() method.
    }

    public function delete(Attachment $attachment): void
    {
        // Delete related DiAttachment entities
        $diAttachments = $this->diAttachmentRepository->findBy(['attachment' => $attachment]);
        foreach ($diAttachments as $diAttachment) {
            $this->manager->remove($diAttachment);
        }

        // Delete related CarpetDesignOrderAttachment entities
        $carpetDesignOrderAttachments = $this->carpetDesignOrderAttachmentRepository->findBy(['attachment' => $attachment]);
        foreach ($carpetDesignOrderAttachments as $carpetDesignOrderAttachment) {
            $this->manager->remove($carpetDesignOrderAttachment);
        }

        $imageAttachment = $attachment->getImageAttachment();
        if ($imageAttachment) {
            $image = $imageAttachment->getImage();
            if ($image) {
                $this->imageRepository->deleteById($image->getId());
            }
            $this->manager->remove($imageAttachment);
        }


        // Delete the Attachment itself
        $this->manager->remove($attachment);
        $this->manager->flush();
    }
}
