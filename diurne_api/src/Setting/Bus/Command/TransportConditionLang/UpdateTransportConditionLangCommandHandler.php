<?php

namespace App\Setting\Bus\Command\TransportConditionLang;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\LanguageRepository;
use App\Setting\Repository\TransportConditionLangRepository;
use App\Setting\Repository\TransportConditionRepository;
use Doctrine\ORM\Exception\ORMException;

class UpdateTransportConditionLangCommandHandler implements CommandHandler
{
    public function __construct(private readonly TransportConditionLangRepository $transportConditionLangRepository, private readonly TransportConditionRepository $transportConditionRepository, private readonly LanguageRepository $languageRepository)
    {
    }

    /**
     * @throws ORMException
     */
    public function __invoke(UpdateTransportConditionLangCommand $command): TransportConditionLangResponse
    {
        $transportConditionLang = $this->transportConditionLangRepository->find($command->getId());
        if (!$transportConditionLang) {
            throw new ResourceNotFoundException();
        }
        $language = $this->languageRepository->find($command->getLangId());
        if (!$language) {
            throw new InvalidArgumentException('Language not found');
        }
        $transportConditionLang->setLabel($command->getLabel());
        $transportConditionLang->setDescription($command->getDescription());
        $transportConditionLang->setLanguage($language);

        $this->transportConditionLangRepository->save($transportConditionLang, true);

        return new TransportConditionLangResponse($transportConditionLang);
    }
}
