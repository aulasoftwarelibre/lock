<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Message\SendNewActivationCodeMessage;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

class AddUserCommand extends Command
{
    protected static $defaultName = 'app:add-user';

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserRepository $userRepository,
        private readonly GoogleAuthenticatorInterface $googleAuthenticator,
        private readonly MessageBusInterface $messageBus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Create a new user')
            ->addArgument('username', InputArgument::REQUIRED, 'Set username')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'Set admin flag')
            ->addOption('super', null, InputOption::VALUE_NONE, 'Set super admin flag');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io       = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');

        if (empty($username)) {
            $io->error('Username can not be empty');

            return Command::FAILURE;
        }

        $user = $this->userRepository->findOneBy(['username' => $username]);

        if ($user !== null) {
            $io->error('Username already exists');

            return Command::FAILURE;
        }

        $secret = $this->googleAuthenticator->generateSecret();

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($username . '@uco.es');
        $user->setGoogleAuthenticatorSecret($secret);

        if ($input->hasOption('admin')) {
            $user->setRoles(['ROLE_ADMIN']);
        }

        if ($input->hasOption('super')) {
            $user->setRoles(['ROLE_SUPER_ADMIN']);
        }

        $this->em->persist($user);
        $this->em->flush();

        $this->messageBus->dispatch(
            new SendNewActivationCodeMessage($username)
        );

        $io->success('User was created.');

        return Command::SUCCESS;
    }
}
