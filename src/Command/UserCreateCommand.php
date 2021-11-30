<?php

namespace App\Command;

use App\Entity\AppUser;
use App\Entity\Role;
use Container2DdvE9b\getDataFixtureCommandService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\ORM\EntityManagerInterface;
use App\Command\DataFixtureCommand;

#[AsCommand(
    name: 'app:user:create',
)]
class UserCreateCommand extends Command
{
    public function __construct(private EntityManagerInterface $em,string $name = null)
    {
        parent::__construct($name);
    }
    protected function configure(): void
    {
        $this
            ->addOption('nickname','nick', InputOption::VALUE_REQUIRED, 'The username of the user.')
            ->addOption('password','pass', InputOption::VALUE_REQUIRED, 'The password of the user.')
            ->addOption('email', 'em',InputOption::VALUE_REQUIRED, 'The email of the user.')
            ->addOption('role', 'ro',InputOption::VALUE_REQUIRED, 'The role of the user.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if($this->checkIfUserExists($input->getOption('nickname'))||
           $this->checkIfEmailExists($input->getOption('email')))
        {
            $output->writeln("Użytkownik o takim nickname lub email już istnieje!");
            return Command::FAILURE;
        }
        $role = $this->checkIfRoleExists($input->getOption('role'));

        $output->writeln('Username: '.$input->getOption('nickname'));
        $output->writeln('Password: '.$input->getOption('password'));
        $output->writeln('Email: '.$input->getOption('email'));
        $output->writeln('Role: '.$input->getOption('role'));

        $user = (new AppUser())
            ->setNickname($input->getOption('nickname'))
            ->setPassword($input->getOption('password'))
            ->setEmail($input->getOption('email'))
            ->setAvatarFileName('testDupa')
            ->setIsBanned(false)
            ->setIsActive(true)
            ->addRole($role);

        $this->em->persist($user);
        $this->em->flush();

        return Command::SUCCESS;
    }
    private function checkIfUserExists(string $nickName):?AppUser {
        return $this->em->getRepository(AppUser::class)->findOneBy(['nickname' => $nickName]);
    }
    private function checkIfRoleExists(string $roleName):?Role {
        return $this->em->getRepository(Role::class)->findOneBy(['name' => $roleName]);
    }
    private function checkIfEmailExists(string $email):?AppUser{
        return $this->em->getRepository(AppUser::class)->findOneBy(['email' => $email]);
    }

}