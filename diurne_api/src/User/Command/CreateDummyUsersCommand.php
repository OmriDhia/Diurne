<?php

namespace App\User\Command;

use App\User\Entity\Gender;
use App\User\Entity\Profile;
use App\User\Entity\User;
use App\User\Repository\GenderRepository;
use App\User\Repository\ProfileRepository;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-dummy-users',
    description: 'Creates dummy users for testing purposes'
)]
class CreateDummyUsersCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProfileRepository $profileRepository,
        private readonly UserRepository $userRepository,
        private readonly GenderRepository $genderRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('profile', InputArgument::REQUIRED, 'Profile name for the dummy users');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $profileName = $input->getArgument('profile');

        // Fetch the profile from the database
        $profile = $this->profileRepository->findOneBy(['name' => $profileName]);

        if (!$profile) {
            $io->error(sprintf('Profile "%s" not found.', $profileName));

            return Command::FAILURE;
        }

        $io->writeln(sprintf('Removing all dummy users with profile "%s"...', $profileName));

        // Remove all dummy users with the specified profile
        $users = $this->userRepository->findBy(['profile' => $profile]);
        foreach ($users as $user) {
            if ($user->isDummyUser()) {
                $this->entityManager->remove($user);
            }
        }
        $this->entityManager->flush();

        $io->writeln(sprintf('Creating dummy users with profile "%s"...', $profileName));

        // Assuming Gender entity exists and genders are already populated in the database
        $genderMale = $this->genderRepository->findOneBy(['name' => 'Mr']);
        $genderFemale = $this->genderRepository->findOneBy(['name' => 'Mme']);

        if (!$genderMale || !$genderFemale) {
            $io->error('Genders "Mr" and/or "Mme" not found.');

            return Command::FAILURE;
        }

        // Create dummy users
        $users = [
            ['email' => 'user1@example.com', 'firstname' => 'John', 'lastname' => 'Doe', 'roles' => ['ROLE_USER'], 'gender' => $genderMale],
            ['email' => 'user2@example.com', 'firstname' => 'Jane', 'lastname' => 'Doe', 'roles' => ['ROLE_USER'], 'gender' => $genderFemale],
            ['email' => 'user3@example.com', 'firstname' => 'Jim', 'lastname' => 'Beam', 'roles' => ['ROLE_USER'], 'gender' => $genderMale],
            ['email' => 'user4@example.com', 'firstname' => 'Janet', 'lastname' => 'Smith', 'roles' => ['ROLE_USER'], 'gender' => $genderFemale],
            ['email' => 'user5@example.com', 'firstname' => 'Mike', 'lastname' => 'Ross', 'roles' => ['ROLE_USER'], 'gender' => $genderMale],
            ['email' => 'user6@example.com', 'firstname' => 'Rachel', 'lastname' => 'Zane', 'roles' => ['ROLE_USER'], 'gender' => $genderFemale],
            ['email' => 'user7@example.com', 'firstname' => 'Alice', 'lastname' => 'Wonder', 'roles' => ['ROLE_USER'], 'gender' => $genderFemale],
            ['email' => 'user8@example.com', 'firstname' => 'Bob', 'lastname' => 'Builder', 'roles' => ['ROLE_USER'], 'gender' => $genderMale],
            ['email' => 'user9@example.com', 'firstname' => 'Charlie', 'lastname' => 'Chaplin', 'roles' => ['ROLE_USER'], 'gender' => $genderMale],
            ['email' => 'user10@example.com', 'firstname' => 'Eve', 'lastname' => 'Adams', 'roles' => ['ROLE_USER'], 'gender' => $genderFemale],
        ];

        foreach ($users as $userData) {
            $user = User::createDummyUser(
                $userData['email'],
                '123@123@1234',
                $userData['firstname'],
                $userData['lastname'],
                array_merge($userData['roles'], ['ROLE_DUMMY']),
                $profile,
                $userData['gender']
            );
            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();

        $io->success('Dummy users created successfully.');

        return Command::SUCCESS;
    }
}
