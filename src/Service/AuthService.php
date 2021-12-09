<?php

namespace App\Service;

use App\Entity\AppUser;
use App\Exception\LoginFailedException;
use App\Exception\UserNotFoundException;
use App\Exception\ValidatorWrongArgsCountException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService
{
    public function __construct(private EntityManagerInterface $em,
                                private UserPasswordHasherInterface $passwordHasher,
                                private SessionInterface $session){}

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

    public function generateUserToken(string $nickname)
    {
        $token = random_int(PHP_INT_MIN, PHP_INT_MAX);
        $token = md5($token);

        /** @var AppUser $user */
        $user = $this->em->getRepository(AppUser::class)->findOneBy(["nickname" => $nickname]);
        $this->session->set("sessionToken", $token);
        $this->session->set("id", $user->getId());
        $user->setToken($token);
        $user->setTokenExpireDate(new \DateTime('now +1 year'));

        $this->em->persist($user);

        $this->em->flush();
    }

    public function authorize()
    {
        $sesToken = $this->session->get("sessionToken");
        $sesId = $this->session->get("id");

        /** @var AppUser $user */
        $user = $this->em->getRepository(AppUser::class)->findOneBy(["id" => $sesId]);

        if($sesToken != $user->getToken() ||
            $user->getTokenExpireDate() < new \DateTime("now")
        )
            throw new AccessDeniedException();
    }

    public function isAuthorized(int $id)
    {
        if($id != $this->session->get("id"))
            throw new AccessDeniedException();
    }
}