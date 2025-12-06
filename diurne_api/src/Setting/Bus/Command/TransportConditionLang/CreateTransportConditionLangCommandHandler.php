<?php

namespace App\Setting\Bus\Command\TransportConditionLang;

use InvalidArgumentException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\TransportConditionLang;
use App\Setting\Repository\LanguageRepository;
use App\Setting\Repository\TransportConditionLangRepository;
use App\Setting\Repository\TransportConditionRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class CreateTransportConditionLangCommandHandler implements CommandHandler
{
    public function __construct(private readonly TransportConditionLangRepository $transportConditionLangRepository, private readonly TransportConditionRepository $transportConditionRepository, private readonly LanguageRepository $languageRepository)
    {
    }

    public function __invoke(CreateTransportConditionLangCommand $command): TransportConditionLangResponse
    {
        $transportCondition = $this->transportConditionRepository->find($command->getTransportConditionId());
        if (!$transportCondition) {
            throw new InvalidArgumentException('Transport condition not found');
        }
        $transportConditionLang = new TransportConditionLang();
        $transportConditionLang->setTransportCondition($transportCondition);
        $transportConditionLang->setLabel($command->getLabel());
        $transportConditionLang->setDescription($command->getDescription());
        $language = $this->languageRepository->find($command->getLangId());
        if (!$language) {
            throw new InvalidArgumentException('Language not found');
        }
        $transportConditionLang->setLanguage($language);

        try {
            $this->transportConditionLangRepository->save($transportConditionLang, true);
        } catch (UniqueConstraintViolationException) {
            throw new Exception('A record with this transport condition and language already exists.');
        }

        return new TransportConditionLangResponse($transportConditionLang);
    }
}
