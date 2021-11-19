<?php

namespace App\Command;

use App\Entity\AppUser;
use App\Entity\Category;
use App\Entity\Permission;
use App\Entity\Post;
use App\Entity\Role;
use App\Entity\Topic;
use App\Repository\AppUserRepository;
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
    const CATEGORY = [
        [
            "categoryName" => "GaySection"
        ]
    ];
    const TOPIC = [
        [
            "topicName" => "Gay",
            "category" => "GaySection"
        ]
    ];
    const POST = [
        [
            "postName" => "ILike",
            "topic" => "Gay",
            "content" => "Dupa<3"
        ]
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
        $this->categoryFixture();
        $this->topicFixture();
        $this->postFixture();


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
    private function categoryFixture() {
        foreach (self::CATEGORY as $categoryData) {
            if($this->checkIfCategoryExists($categoryData["categoryName"]))
                continue;

            $creator = $this->checkIfUserExists('JacekSoplica69');

            if(!$creator)
                continue;

            $category = (new Category())
                ->setCategoryName($categoryData["categoryName"])
                ->setCreator($creator);

            $this->em->persist($category);

        }
        $this->em->flush();
    }

    private function topicFixture() {
        foreach (self::TOPIC as $topicData) {
            if($this->checkIfTopicExists($topicData["topicName"]))
                continue;
            $category = $this->checkIfCategoryExists($topicData["category"]);

            if(!$category)
                continue;

            $creator = $this->checkIfUserExists('JacekSoplica69');

            if(!$creator)
                continue;

            $topic = (new Topic())
                ->setTopicName($topicData["topicName"])
                ->setCreator($creator)
                ->setCategory($category);

            $this->em->persist($topic);
        }
        $this->em->flush();
    }
    private function postFixture() {
        foreach (self::POST as $postData) {
            if($this->checkIfPostExists($postData["postName"]))
                continue;
            $topic = $this->checkIfTopicExists($postData["topic"]);
            if(!$topic)
                continue;

            $creator = $this->checkIfUserExists('JacekSoplica69');

            if(!$creator)
                continue;

            $post = (new Post())
                ->setPostName($postData["postName"])
                ->setCreator($creator)
                ->setTopic($topic)
                ->setContent($postData["content"]);

            $this->em->persist($post);
        }
        $this->em->flush();
    }
    private function checkIfPermissionExists(string $permissionName):?Permission {
        return $this->em->getRepository(Permission::class)->findOneBy(['token' => $permissionName]);
    }
    private function checkIfRoleExists(string $roleName):?Role {
        return $this->em->getRepository(Role::class)->findOneBy(['name' => $roleName]);
    }
    private function checkIfUserExists(string $nickName):?AppUser {
        return $this->em->getRepository(AppUser::class)->findOneBy(['nickname' => $nickName]);
    }
    private function checkIfCategoryExists(string $categoryName):?Category {
        return $this->em->getRepository(Category::class)->findOneBy(['categoryName' => $categoryName]);
    }
    private function checkIfTopicExists(string $topicName):?Topic {
        return $this->em->getRepository(Topic::class)->findOneBy(['topicName' => $topicName]);
    }
    private function checkIfPostExists(string $postName):?Post {
        return $this->em->getRepository(Post::class)->findOneBy(['postName' => $postName]);
    }
}
