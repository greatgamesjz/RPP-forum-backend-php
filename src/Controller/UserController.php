<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(private UserService $userService){}

    /**
     * @Route("/api/users", name="get_users" method="GET")
     */
    public function getAppUsers(): Response
    {
        return $this->json($this->userService->get());
    }
    /**
     * @Route("/api/user/{id}", name="get_user" method="GET")
     */
    public function getAppUser(int $id): Response
    {
        return $this->json($this->userService->get($id));
    }
}
