<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\GetLanguages;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\LanguageRepository;

final readonly class GetLanguagesQueryHandler implements QueryHandler
{
    public function __construct(private LanguageRepository $languageRepository)
    {
    }

    public function __invoke(GetLanguagesQuery $query)
    {
        $countries = $this->languageRepository->findAll();

        $formattedLanguages = array_map(fn($language) => [
            'language_id' => $language->getId(),
            'name' => $language->getName(),
            'is_code' => $language->getIsoCode(),
        ], $countries);

        return new GetLanguagesResponse(
            $formattedLanguages
        );
    }
}
