<?php

namespace App\Service;

use App\Entity\AppUser;
use App\Exception\LoginFailedException;
use App\Exception\UserNotFoundException;
use App\Exception\ValidatorWrongArgsCountException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService
{
    public function __construct(private EntityManagerInterface $em,
                                private UserPasswordHasherInterface $passwordHasher){}

    /**
     * @throws UserNotFoundException
     * @throws LoginFailedException
     */
    public function checkIfPasswordValid(?string $nickname, ?string $password): void{

        if(!$nickname || !$password)
            throw new LoginFailedException();

        $user = $this->em->getRepository(AppUser::class)->findOneBy(["nickname" => $nickname]);
        if(!$user)
            throw new UserNotFoundException($nickname);

         $this->passwordHasher->isPasswordValid($user, $password) ?: throw new LoginFailedException();
    }
}