<?php

namespace App\Service;

use App\Entity\AppUser;
use App\Exception\ValidatorDataSetException;
use App\Exception\ValidatorEmaiIExistsException;
use App\Exception\ValidatorWrongCharacterEmailException;
use App\Validator\CategoryValidator\CategoryNameValidator;
use App\Validator\CategoryValidator\CategoryPasswordValidator;
use App\Validator\UserValidator\UserEmailValidator;
use App\Validator\ValidatorDecorator;
use Doctrine\ORM\EntityManagerInterface;

class UserService implements CrudInterface
{
    public function __construct(private EntityManagerInterface $em){}


    /**
     * @param array $data
     * @throws ValidatorDataSetException
     * @throws ValidatorWrongCharacterEmailException
     * @throws ValidatorEmaiIExistsException
     */
    public function add(array $data)
    {
        $validator = (new ValidatorDecorator());
        $validator->setData($data);
        $validator = new CategoryNameValidator($validator);
        $validator = new CategoryPasswordValidator($validator);
        $validator = new UserEmailValidator($validator);
        $validator->setem($this->em);
        $validator->validate();
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