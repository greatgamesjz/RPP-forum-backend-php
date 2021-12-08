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

class UserController extends AbstractController
{
    public function __construct(private UserService $userService){}

    /**
     * @Route("/api/user/get/all", name="get_users", methods={"GET"})
     */
    public function getAppUsers(): JsonResponse
    {
        return $this->json($this->userService->getAll());
    }

    /**
     * @Route("/api/user/get/{id}", name="get_user", methods={"GET"}, requirements={"id"="^[0-9]*$"})
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
