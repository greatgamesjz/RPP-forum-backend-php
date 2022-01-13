<?php

namespace App\Controller;

use App\Exception\CategoryNotFoundException;
use App\Exception\UserNotFoundException;
use App\Exception\ValidatorDataSetException;
use App\Exception\ValidatorEmaiIExistsException;
use App\Exception\ValidatorIdDoNotExists;
use App\Exception\ValidatorWrongCharacterCountException;
use App\Exception\ValidatorWrongCharacterEmailException;
use App\Exception\ValidatorWrongCharacterPasswordException;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as OA;

class UserController extends AbstractController
{
    public function __construct(private UserService $userService){}

    /**
     * @Route("/api/user/get/all", name="get_users", methods={"GET"})
     * @OA\Response (response=200, description="Zwraca wszystkich użytkowników.")
     * @OA\Tag (name="User")
     */
    public function getAppUsers(): JsonResponse
    {
        return $this->json($this->userService->getAll());
    }

    /**
     * @Route("/api/user/get/{id}", name="get_user", methods={"GET"}, requirements={"id"="^[0-9]*$"})
     * @OA\Response (response=200, description="Zwraca użytkownika.")
     * @OA\Tag (name="User")
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
     * @OA\Response (response=200, description="Dodaje użytkownika.")
     * @OA\Tag (name="User")
     */
    public function addUser(Request $request): JsonResponse
    {
        try{
            $this->userService->add($request->request->all());
            return $this->json("success");
        }catch(ValidatorWrongCharacterEmailException|ValidatorDataSetException|ValidatorEmaiIExistsException $e){
            return $this->json($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Route("/api/user/update", name="user_update", methods={"PATCH"})
     * @OA\Response (response=200, description="Edytuje użytkownika.")
     * @OA\Tag (name="User")
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $this->userService->update($request->request->get("id"),$request->request->all());
        } catch (CategoryNotFoundException|ValidatorDataSetException|ValidatorEmaiIExistsException|
        ValidatorIdDoNotExists|ValidatorWrongCharacterEmailException|ValidatorWrongCharacterCountException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("success");
    }

    /**
     * @Route("/api/user/update/password", name="update_password", methods={"PATCH"})
     * @OA\Response (response=200, description="Edytuje hasło użytkownika.")
     * @OA\Tag (name="User")
     */
    public function updatePassword(Request $request): JsonResponse
    {
        try {
            $this->userService->updatePassword($request->request->all());
        }catch (ValidatorDataSetException | ValidatorWrongCharacterPasswordException | CategoryNotFoundException $e){
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("Success");
    }
}
