<?php

namespace App\Controller;

use App\Exception\CategoryNotFoundException;
use App\Exception\PrivateMessageNotFoundException;
use App\Exception\UserNotFoundException;
use App\Exception\ValidatorDataSetException;
use App\Exception\ValidatorExceptionInterface;
use App\Exception\ValidatorWrongArgsCountException;
use App\Exception\ValidatorWrongCharacterCountException;
use App\Service\CategoryService;
use App\Service\PrivateMessageService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class PrivateMessageController extends AbstractController
{
    public function __construct(private PrivateMessageService $privateMessageService){}

    /**
     * @Route("/api/pm/add", name="add_pm", methods={"POST"})
     */
    public function addPrivateMessage(Request $request): JsonResponse{
        try {
            $this->privateMessageService->add($request->request->all());
        }catch(UserNotFoundException $e){
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("SUCCES");

    }

    /**
     * @Route("/api/pm/delete/{id}", name="delete_pm", methods={"DELETE"}, requirements={"id"="^[0-9]*$"})
     */
    public function deletePrivateMessage(int $id): JsonResponse{
        try {
            $this->privateMessageService->delete($id);
        }catch (PrivateMessageNotFoundException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("SUCCES");
    }

    /**
     * @Route("/api/pm/update/{id}", name="update_pm", methods={"PATCH"})
     */
    public function updatePrivateMessage(int $id, Request $request) : JsonResponse{
        try {
            $this->privateMessageService->update($id, $request->request->all());
        } catch (PrivateMessageNotFoundException $e){
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("SUCCES");
    }

    /**
     * @Route("/api/pm/get/{id}", name="get_pm", methods={"GET"}, requirements={"id"="^[0-9]*$"})
     */
    public function getPrivateMessage(int $id): JsonResponse
    {
        try {
            return $this->json(json_encode($this->privateMessageService->get($id)));
        } catch (PrivateMessageNotFoundException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Route("/api/pm/get/all", name="get_pm", methods={"GET"})
     */
    public function getAllPrivateMessages(Request $query): JsonResponse
    {
        return $this->json(
            json_encode(
                $this->privateMessageService->getAll($query->query->all())
            )
        );
    }
}