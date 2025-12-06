<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetProjectDisByContremarque;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\ProjectDiRepository;
use App\User\Repository\ProfileRepository;
use App\User\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class GetProjectDisByContremarqueQueryHandler implements QueryHandler
{
    public function __construct(private ProjectDiRepository $projectDiRepository, private RequestStack $requestStack, private UserRepository $userRepository, private ProfileRepository $profileRepository)
    {
    }

    public function __invoke(GetProjectDisByContremarqueQuery $query): GetProjectDisByContremarqueResponse
    {
        $session = $this->requestStack->getSession();
        $user = $session->get('user');
        $profile = $this->profileRepository->find((int) $user->getProfile()->getId());
        if (in_array($profile->getName(), ['Designer manager'])) {
            $projectDis = $this->projectDiRepository->findBy(['contremarque' => $query->getContremarqueId(), 'transmitted_to_studio' => true]);
        } elseif (in_array($profile->getName(), ['Designer'])) {
            $projectDis = $this->projectDiRepository->findByContremarqueAndDesigner($query->getContremarqueId(), $user->getId());
        } else {
            $projectDis = $this->projectDiRepository->findBy(['contremarque' => $query->getContremarqueId()]);
        }

        $formattedProjectDis = array_map(fn ($projectDi) => $projectDi->toArray(), $projectDis);

        return new GetProjectDisByContremarqueResponse($formattedProjectDis);
    }
}
