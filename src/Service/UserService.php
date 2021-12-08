<?php

namespace App\Service;

use App\Entity\AppUser;
use App\Entity\Category;
use App\Exception\CategoryNotFoundException;
use App\Exception\UserNotFoundException;
use App\Exception\ValidatorIdDoNotExists;
use App\Exception\ValidatorWrongCharacterCountException;
use App\Exception\ValidatorWrongCharacterPasswordException;
use App\Exception\ValidatorDataSetException;
use App\Exception\ValidatorEmaiIExistsException;
use App\Exception\ValidatorWrongCharacterEmailException;
use App\Validator\UserValidator\UserIdValidator;
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

    /**
     * @throws ValidatorDataSetException
     * @throws ValidatorWrongCharacterCountException
     * @throws ValidatorWrongCharacterEmailException
     * @throws CategoryNotFoundException
     * @throws ValidatorEmaiIExistsException
     * @throws ValidatorIdDoNotExists
     */
    public function update(int $id, array $data)
    {
        if(array_key_exists("nickname",$data))
        {
            $this->updateNickname($data);
        }
        if (array_key_exists("email",$data))
        {
            $this->updateEmail($data);
        }
    }

    /**
     * @param int $id
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

    /**
     * @throws ValidatorDataSetException
     * @throws ValidatorWrongCharacterCountException
     * @throws CategoryNotFoundException
     * @throws \App\Exception\ValidatorIdDoNotExists
     */
    public function updateNickname(array $data)
    {
        $validator = (new ValidatorDecorator());
        $validator->setData($data);
        $validator = new UserNicknameValidator($validator);
        $validator = new UserIdValidator($validator);
        $validator->setem($this->em);
        $validator->validate();

        /** @var AppUser $nickname */
        $nickname = $this->em->getRepository(appuser::class)->findOneBy(["id" => $data["id"]]);
        if(!$nickname)
            throw new CategoryNotFoundException($data["id"]);
        $nickname->setNickname($data["nickname"] ?? $nickname->getNickname());
        $this->em->persist($nickname);
        $this->em->flush();
    }

    /**
     * @throws ValidatorDataSetException
     * @throws ValidatorEmaiIExistsException
     * @throws ValidatorWrongCharacterEmailException
     * @throws CategoryNotFoundException
     */
    public function updateEmail(array $data)
    {
        $validator = (new ValidatorDecorator());
        $validator->setData($data);
        $validator = new UserEmailValidator($validator);
        $validator->setem($this->em);
        $validator->validate();

        /** @var AppUser $email */
        $email = $this->em->getRepository(appuser::class)->findOneBy(["id" => $data["id"]]);
        if(!$email)
            throw new CategoryNotFoundException($data["id"]);
        $email->setEmail($data["email"] ?? $email->getEmail());
        $this->em->persist($email);
        $this->em->flush();
    }

    /**
     * @throws ValidatorDataSetException
     * @throws ValidatorWrongCharacterPasswordException
     * @throws CategoryNotFoundException
     */
    public function updatePassword(array $data)
    {
        $validator = (new ValidatorDecorator());
        $validator->setData($data);
        $validator = new UserPasswordValidator($validator);
        $validator->validate();

        /** @var AppUser $password */
        $password = $this->em->getRepository(appuser::class)->findOneBy(["id" => $data["id"]]);
        if(!$password)
            throw new CategoryNotFoundException($data["id"]);
        $password->setPassword($data["password"] ?? $password->getPassword());
        $this->em->persist($password);
        $this->em->flush();
    }
}