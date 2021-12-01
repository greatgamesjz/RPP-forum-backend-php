<?php

namespace App\Controller;

use App\Exception\UserNotFoundException;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    public function __construct(private UserService $userService){}

    /**
     * @Route("/api/user/get/all", name="get_users", methods={"GET"})
     */
    public function getAppUsers(): Response
    {
        return $this->json($this->userService->getAll());
    }

    /**
     * @Route("/api/user/get/{id}", name="get_user", methods={"GET"})
     */
    public function getAppUser(int $id): Response
    {
        try {
            return $this->json(json_encode(($this->userService->get($id))));
        } catch (UserNotFoundException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Route("/api/user/add", name="add_user", methods={"POST"})
     */
    public function addUser(Request $request): Response
    {
        $this->userService->newUserData($request->request->all());
        return new Response(
            '<html><body>Your nickname: '.$request->request->get('name').'</body></html>'
        );
    }
}
