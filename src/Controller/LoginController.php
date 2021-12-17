<?php

namespace App\Controller;

use App\Exception\LoginFailedException;
use App\Exception\UserAlreadyActiveException;
use App\Exception\UserNotActiveException;
use App\Service\AuthService;
use App\Exception\UserNotFoundException;
use App\Exception\ValidatorDataSetException;
use App\Exception\ValidatorEmaiIExistsException;
use App\Exception\ValidatorWrongCharacterCountException;
use App\Exception\ValidatorWrongCharacterEmailException;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class LoginController extends AbstractController
{
    public function __construct(private AuthService $authService){}

    /**
     * @Route("/api/login", name="login", methods={"POST"})
     */
    public function login(Request $request): JsonResponse{
        try {
            $this->authService->checkIfPasswordValid(
                $request->request->get("nickname"),
                $request->request->get("password")
                );
            $this->authService->generateUserToken($request->request->get("nickname"));

        }catch(LoginFailedException|UserNotFoundException|AccessDeniedException $e){
            return $this->json($e->getMessage(), RESPONSE::HTTP_FORBIDDEN);
        }catch(UserNotActiveException $e){
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("success");
    }

    /**
     * @Route("/api/authorize", name="authorize", methods={"GET"})
     */
    public function checkSession(): JsonResponse{
        try {
            $this->authService->authorize();
        }catch(AccessDeniedException $e){
            return $this->json($e->getMessage(), RESPONSE::HTTP_FORBIDDEN);
        }
        return $this->json("success");
    }

    /**
     * @Route("/api/getactivationlink/{id}", name="get_activationlink", methods={"GET"}, requirements={"id"="^[0-9]*$"})
     */
    public function getActivationLink(int $id): JsonResponse{
        try {
            $this->authService->getActivationLink($id);
        }catch(UserNotFoundException $e){
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("Success");
    }

    /**
     * @Route("/api/activate/{token}", name="activate", methods={"GET"})
     */
    public function activateUser(string $token): JsonResponse{
        try {
            $this->authService->activateUser($token);
        }catch(UserNotFoundException | UserAlreadyActiveException $e){
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("Success");
    }
}
