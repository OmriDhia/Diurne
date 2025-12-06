<?php

namespace App\Contremarque\Bus\Query\GetContremarqueByPrescriberId;

use App\Contremarque\Repository\ContremarqueRepository;
use App\Common\Bus\Query\QueryHandler;
use Doctrine\ORM\EntityManagerInterface;

class GetContremarqueByPrescriberIdQueryHandler implements QueryHandler
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly ContremarqueRepository $contremarqueRepository)
    {
    }

    public function __invoke(GetContremarqueByPrescriberIdQuery $query): ContremarqueResponse
    {
        // Fetch all Contremarques by prescriberId
        $contremarques = $this->contremarqueRepository->findBy(['prescriber' => $query->prescriberId]);

        return new ContremarqueResponse($contremarques);
    }
}
