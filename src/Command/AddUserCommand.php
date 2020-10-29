<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddUserCommand extends Command
{
    protected static $defaultName = 'app:add-user';
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        parent::__construct();

        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Create a new user')
            ->addArgument('username', InputArgument::REQUIRED, 'Set username')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'Set admin flag')
            ->addOption('super', null, InputOption::VALUE_NONE, 'Set super admin flag')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');

        if (empty($username)) {
            $io->error('Username can not be empty');

            return Command::FAILURE;
        }

        $user = $this->userRepository->findOneBy(['username' => $username]);

        if ($user) {
            $io->error('Username already exists');

            return Command::FAILURE;
        }

        $user = new User();
        $user->setUsername($username);

        if ($input->hasOption('admin')) {
            $user->setRoles(['ROLE_ADMIN']);
        }

        if ($input->hasOption('super')) {
            $user->setRoles(['ROLE_SUPER_ADMIN']);
        }

        $this->em->persist($user);
        $this->em->flush();

        $io->success('User was created.');

        return Command::SUCCESS;
    }
}
