<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use DomainException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\Language;
use App\Setting\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMLanguageRepository extends DoctrineORMRepository implements LanguageRepository
{
    protected const ENTITY_CLASS = Language::class;
    protected const ALIAS = 'language';

    /**
     * DoctrineORMLanguageRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @return |null
     */
    public function findByName($name)
    {
        try {
            $object = $this->query()
                ->where('language.name = :name')
                ->setParameter('name', $name)
                ->getQuery()->getSingleResult();
        } catch (NoResultException) {
            return null;
        } catch (NonUniqueResultException) {
            throw new DomainException('More than one result found');
        }

        return $object;
    }

    /**
     * @return Language
     */
    public function create(array $data)
    {
        $language = new Language();
        $language->setName($data['name']);
        $language->setIsoCode($data['iso_code']);
        $this->persist($language);
        $this->flush();

        return $language;
    }

    /**
     * @param object $language
     *
     * @return object
     */
    public function update($language, array $data)
    {
        $language->setName($data['name']);
        $language->setIsoCode($data['iso_code']);
        $this->persist($language);
        $this->flush();

        return $language;
    }

    public function selectRandomLanguage(): object|null
    {
        $sql = 'SELECT id FROM `language` ORDER BY RAND() ASC LIMIT 1';
        $stmt = $this->manager->getConnection()->prepare($sql);
        $id = $stmt->execute()->fetchOne();
        if (empty($id)) {
            return $this->findOneBy(['iso_code' => 'fr']);
        }

        return $this->find((int) $id);
    }
}
