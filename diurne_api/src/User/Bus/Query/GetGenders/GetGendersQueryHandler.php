<?php

declare(strict_types=1);

namespace App\User\Bus\Query\GetGenders;

use App\Common\Bus\Query\QueryHandler;
use App\User\Repository\GenderRepository;

final readonly class GetGendersQueryHandler implements QueryHandler
{
    public function __construct(private GenderRepository $genderRepository)
    {
    }

    public function __invoke(GetGendersQuery $query)
    {
        $genders = $this->genderRepository->findAll();

        $formattedGenders = array_map(fn($gender) => [
            'gender_id' => $gender->getId(),
            'name' => $gender->getName(),
        ], $genders);

        return new GetGendersResponse(
            $formattedGenders
        );
    }
}
