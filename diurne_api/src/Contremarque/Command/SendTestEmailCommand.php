<?php
// src/Command/SendTestEmailCommand.php
namespace App\Contremarque\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsCommand(
    name: 'app:test-email',
    description: 'Sends a test email'
)]
class SendTestEmailCommand extends Command
{
    public function __construct(private readonly MailerInterface $mailer)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = (new Email())
            ->from('no-reply@example.com')
            ->to('your-email@example.com') // Replace with your email address
            ->subject('Test Email from Symfony')
            ->text('This is a test email sent from Symfony via console command!');

        $this->mailer->send($email);

        $output->writeln('Test email sent!');

        return Command::SUCCESS;
    }
}
