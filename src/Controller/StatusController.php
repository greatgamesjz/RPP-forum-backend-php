<?php

namespace App\Controller;

use App\Exception\StatusNotFoundException;
use App\Exception\UserNotFoundException;
use App\Service\StatusService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class StatusController extends AbstractController
{
    public function __construct(private StatusService $statusService)
    {
    }

    /**
     * @Route("/api/status/get/{id}", name="get_status", methods={"GET"}, requirements={"id"="^[0-9]*$"})
     * @OA\Response (response=200, description="Zwraca status.")
     * @OA\Tag (name="Status")
     */
    public function getStatus(int $id): JsonResponse
    {
        try {
            return $this->json(json_encode(($this->statusService->get($id))));
        } catch(StatusNotFoundException  $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Route("/api/status/add", name="add_status", methods={"POST"})
     * @OA\Response (response=200, description="Dodaje status.")
     * @OA\Tag (name="Status")
     */
    public function addStatus(Request $request): JsonResponse
    {
        try {
            $this->statusService->add($request->request->all());
        }catch(UserNotFoundException $e){
            return $this->json($e->getMessage(), $e->getCode());
        }

        return $this->json("Success");
    }

    /**
     * @Route("/api/status/update/{id}", name="update_status", methods={"POST"}, requirements={"id"="^[0-9]*$"})
     * @OA\Response (response=200, description="Edytuje status.")
     * @OA\Tag (name="Status")
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        try {
            $this->statusService->update($id, $request->request->all());
        }catch(StatusNotFoundException $e){
            return $this->json($e->getMessage(), $e->getCode());
        }

        return $this->json("Success");
    }

    /**
     * @Route("/api/status/delete/{id}", name="delete_status", methods={"GET"}, requirements={"id"="^[0-9]*$"})
     * @OA\Response (response=200, description="Usuwa status.")
     * @OA\Tag (name="Status")
     */
    public function deleteStatus(int $id): JsonResponse
    {
        try {
            $this->statusService->delete($id);
        } catch (StatusNotFoundException $e){
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("Success");
    }

    /**
     * @Route("/api/status/getfive", name="getfive_status", methods={"GET"})
     * @OA\Response (response=200, description="Zwraca pięć ostatnich statusów.")
     * @OA\Tag (name="Status")
     */
    public function getFiveStatuses(): JsonResponse
    {
     return $this->json($this->statusService->getFive());
    }
}