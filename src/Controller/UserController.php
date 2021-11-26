<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    public function __construct(private UserService $userService){}

    /**
     * @Route("/api/users", name="get_users", methods={"GET"})
     */
    public function getAppUsers(): Response
    {
        return $this->json($this->userService->get());
    }
    /**
     * @Route("/api/user/{id}", name="get_user", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    /**
     * @Route("/api/user/adduser", name="add_user", methods={"POST"})
     */
    public function addUser(Request $request): Response
    {
        $this->userService->newUserData($request->request->all());
        return new Response(
            '<html><body>Your nickname: '.$request->request->get('name').'</body></html>'
        );
    }
}
