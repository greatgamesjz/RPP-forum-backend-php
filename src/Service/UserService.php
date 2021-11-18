<?php

namespace App\Service;

use App\Entity\AppUser;
use Doctrine\ORM\EntityManagerInterface;

class UserService implements CrudInterface
{
    public function __construct(private EntityManagerInterface $em){}

    public function add(array $data)
    {
        // TODO: Implement add() method.
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }

    public function update(int $id, array $data)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param int|null $id
     * @return AppUser[]
     */
    public function get(int $id = null): array
    {
        $result = [];
        if($id === null) {
            /** @var AppUser[] $users */
            $users = $this->em->getRepository(AppUser::class)->findAll();
            foreach ($users as $user) {
                $result[] = [
                    "name" => $user->getNickname(),
                    "email" => $user->getEmail()
                ];
            }
        } else {
            /** @var AppUser $user */
            $user = $this->em->getRepository(AppUser::class)->findAll();
            $result[] = [
                "name" => $user->getNickname(),
                "email" => $user->getEmail()
            ];
        }
        return $result;
    }
}