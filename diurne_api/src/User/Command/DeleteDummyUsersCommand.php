<?php

namespace App\User\Command;

use App\User\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:user:delete-dummy-users', description: 'Deletes dummy users created by fixtures.')]
class DeleteDummyUsersCommand extends Command
{
    public function __construct(private readonly UserRepository $userRepository)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Fetch dummy users to delete
        $usersToDelete = $this->userRepository->getDummyUser();

        if (empty($usersToDelete)) {
            $io->success('No dummy users found to delete.');
            return Command::SUCCESS;
        }

        // Remove each dummy user
        foreach ($usersToDelete as $user) {
            $this->userRepository->remove($user);
        }

        // Flush changes to the database
        $this->userRepository->flush();

        // Output success message
        $io->success(sprintf('%d dummy users have been deleted.', count($usersToDelete)));

        return Command::SUCCESS;
    }
}
