<?php

namespace App\Command;

use App\Entity\AppUser;
use App\Entity\Permission;
use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:data-fixture',
    description: 'Add a short description for your command',
)]
class DataFixtureCommand extends Command
{
    const PERMISSIONS_ROLES = [
        "ADMIN" => ["USER_BAN", "USER_EDIT", "USER_VIEW"],
        "USER" => ["USER_VIEW"]
    ];
    const USERS = [
        [
            "nickname" => "JacekSoplica69",
            "email" => "ponentnanatalka11@gwalt.com",
            "password" => "CialoChrystusa1",
            "role" => "ADMIN"
        ],
        [
            "nickname" => "wAŁęsa88",
            "email" => "wAŁęsa88@gwalt.com",
            "password" => "konstYtUcja2",
            "role" => "USER"
        ],
    ];


    public function __construct(private EntityManagerInterface $em,string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->getFormatter()->setStyle(
            'fire',
            new OutputFormatterStyle('red', 'red', ['bold', 'blink'])
        );

        $output->getFormatter()->setStyle(
            'fire',
            new OutputFormatterStyle('green', 'green', ['bold'])
        );

        $output->writeln('<red>Loading data...</red>');

        $this->permissionRoleFixture();
        $this->userFixture();

        $output->writeln('<green>Success</green>');

        return Command::SUCCESS;
    }
    private function userFixture() {
        foreach (self::USERS as $userData) {
            if($this->checkIfUserExists($userData["nickname"]))
                continue;

            $role = $this->checkIfRoleExists($userData["role"]);

            if(!$role)
                continue;

            $user = (new AppUser())
                ->setNickname($userData["nickname"])
                ->setEmail($userData["email"])
                ->setPassword($userData["password"])
                ->addRole($role);

            $this->em->persist($user);
        }
        $this->em->flush();
    }
    private function permissionRoleFixture() {
        foreach (self::PERMISSIONS_ROLES as $roleName => $permissions) {
            if($this->checkIfRoleExists($roleName))
                continue;

            $role = (new Role())->setName($roleName);

            foreach ($permissions as $permissionToken) {
                if(($permission = $this->checkIfPermissionExists($permissionToken)) != null) {
                    $permission->addRole($role);
                } else {
                    $permission = (new Permission())
                        ->setName($permissionToken)
                        ->setToken($permissionToken)
                        ->addRole($role);
                    $this->em->persist($permission);
                };
            }

            $this->em->persist($role);
        }
        $this->em->flush();
    }
    private function checkIfPermissionExists(string $permissionName):?Permission {
        return $this->em->getRepository(Permission::class)->findOneBy(['token' => $permissionName]);
    }
    private function checkIfRoleExists(string $roleName):?Role {
        return $this->em->getRepository(Role::class)->findOneBy(['name' => $roleName]);
    }
    private function checkIfUserExists(string $nickName):?Role {
        return $this->em->getRepository(AppUser::class)->findOneBy(['nickname' => $nickName]);
    }
}
