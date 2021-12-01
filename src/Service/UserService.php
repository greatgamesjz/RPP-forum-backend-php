<?php

namespace App\Service;

use App\Entity\AppUser;
use App\Entity\Category;
use App\Exception\UserNotFoundException;
use App\Validator\CategoryValidator\CategoryEmailValidator;
use App\Exception\ValidatorDataSetException;
use App\Exception\ValidatorEmaiIExistsException;
use App\Exception\ValidatorWrongCharacterEmailException;
use App\Validator\UserValidator\UserNicknameValidator;
use App\Validator\UserValidator\UserPasswordValidator;
use App\Validator\UserValidator\UserEmailValidator;
use App\Validator\ValidatorDecorator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserService implements CrudInterface
{
    public function __construct(private EntityManagerInterface $em,  private NormalizerInterface $normalizer){}


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
        $validator = new UserNicknameValidator($validator);
        $validator = new UserPasswordValidator($validator);
        $validator = new UserEmailValidator($validator);
        $validator->setem($this->em);
        $validator->validate();

        unset($validator);

        $category = $this->normalizer->denormalize($data, AppUser::class);

        $this->em->persist($category);

        $this->em->flush();

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
     * @throws UserNotFoundException
     */
    public function get(int $id): array
    {
        $result = [];

            /** @var AppUser $user */
            $user = $this->em->getRepository(AppUser::class)->findOneBy(["id" => $id]);
            if(!$user)
                throw new UserNotFoundException($id);
            $result[] = [
                "name" => $user->getNickname(),
                "email" => $user->getEmail()
            ];
        return $result;
    }
    public function getAll(): array
    {
        $result = [];
        /** @var AppUser[] $users */
        $users = $this->em->getRepository(AppUser::class)->findAll();
        foreach ($users as $user) {
            $result[] = [
                "name" => $user->getNickname(),
                "email" => $user->getEmail()
            ];
        }
        return $result;
    }
}