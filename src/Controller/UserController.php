<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(private UserService $userService){}

    /**
     * @Route("/api/users", name="get_users")
     */
    public function getAppUsers(): Response
    {
        return $this->json($this->userService->get());
    }
    /**
     * @Route("/api/user/{id}", name="get_user")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }
}
