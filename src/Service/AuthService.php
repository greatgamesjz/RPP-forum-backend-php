<?php

namespace App\Service;

use App\Entity\AppUser;
use App\Exception\LoginFailedException;
use App\Exception\UserAlreadyActiveException;
use App\Exception\UserNotActiveException;
use App\Exception\UserNotFoundException;
use App\Exception\ValidatorWrongArgsCountException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class AuthService
{
    public function __construct(private EntityManagerInterface $em,
                                private UserPasswordHasherInterface $passwordHasher,
                                private SessionInterface $session,
                                private MailerInterface $mailer,
                                private RouterInterface $router){}

    /**
     * @throws UserNotFoundException
     * @throws LoginFailedException
     * @throws UserNotActiveException
     */
    public function checkIfPasswordValid(?string $nickname, ?string $password): void{

        if(!$nickname || !$password)
            throw new LoginFailedException();

        /** @var AppUser $user */
        $user = $this->em->getRepository(AppUser::class)->findOneBy(["nickname" => $nickname]);
        if(!$user)
            throw new UserNotFoundException($nickname);

        if(!$user->getIsActive())
            throw new UserNotActiveException();

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
        $user = $this->em->getRepository(AppUser::class)->findOneBy(["id" => $sesId, "isDeleted" => false]);

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

    /**
     * @throws UserNotFoundException
     */
    public function getActivationLink(int $id)
    {
        /** @var AppUser $user */
        $user = $this->em->getRepository(AppUser::class)->findOneBy(["id" => $id, "isActive" => false]);
        if(!$user)
            throw new UserNotFoundException($id);

        $token = random_int(PHP_INT_MIN, PHP_INT_MAX);
        $token = md5($token);

        $user->setActivationToken($token);

        $email = (new Email())
            ->from('roleplejplus@gmail.com')
            ->to($user->getEmail())
            ->subject('Rejestracja na serwer RolePlejPlus!')
            ->text($this->router->generate("activate",["token" => $token], UrlGeneratorInterface::ABSOLUTE_URL));

        $this->mailer->send($email);

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @throws UserAlreadyActiveException
     */
    public function activateUser($token)
    {
        /** @var AppUser $user */
        $user = $this->em->getRepository(AppUser::class)->findOneBy([
            "activationToken" => $token,
            "isDeleted" => false
            ]);

        if(!$user)
            throw new UserAlreadyActiveException();

        if($token == $user->getActivationToken()){
            $user->setIsActive(true);
            $user->setActivationToken(null);
        }

        $this->em->persist($user);
        $this->em->flush();
    }
}