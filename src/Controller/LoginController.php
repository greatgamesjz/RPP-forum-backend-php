<?php

namespace App\Controller;

use App\Exception\LoginFailedException;
use App\Service\AuthService;
use App\Exception\UserNotFoundException;
use App\Exception\ValidatorDataSetException;
use App\Exception\ValidatorEmaiIExistsException;
use App\Exception\ValidatorWrongCharacterCountException;
use App\Exception\ValidatorWrongCharacterEmailException;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        }catch(LoginFailedException|UserNotFoundException $e){
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("success");
    }
}