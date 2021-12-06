<?php

namespace App\Controller;

use App\Exception\TopicNotFoundException;
use App\Exception\ValidatorExceptionInterface;
use App\Service\TopicService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class TopicController extends AbstractController
{
    public function __construct(private TopicService $topicService)
    {
    }

    /**
     * @Route("/api/topic/get/all", name="get_topics", methods={"GET"})
     */
    public function getTopics(): JsonResponse
    {
        return $this->json($this->topicService->getAll());
    }

    /**
     * @Route("/api/topic/get/{id}", name="get_topic", methods={"GET"}, requirements={"id"="^[0-9]*$"})
     */
    public function getTopic(int $id): JsonResponse
    {
        try {
            return $this->json(json_encode(($this->topicService->get($id))));
        } catch (TopicNotFoundException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Route("/api/topic/add", name="add_topic", methods={"POST"})
     */
    public function addTopic(Request $request): JsonResponse
    {
        try {
            $this->topicService->add($request->request->all());
        } catch (ValidatorExceptionInterface $e) {
            return $this->json($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->json("Success");
    }

    /**
     * @Route("/api/topic/delete/{id}", name="delete_topic", methods={"GET"})
     */
    public function deleteTopic(int $id): JsonResponse
    {
        try {
            $this->topicService->delete($id);
        } catch (TopicNotFoundException $e){
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("SUCCES");
    }

    /**
     * @Route("/api/topic/update/{id}", name="update_topic", methods={"PATCH"})
     */
    public function updateTopic(Request $request, int $id): JsonResponse
    {
        try {
            $this->topicService->update($id, $request->request->all());
        } catch (TopicNotFoundException $e){
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("SUCCES");
    }

    /**
     * @Route("/api/topic/get/pageall", name="page_topic", methods={"GET"})
     */
    public function getTopicsPage(Request $query): JsonResponse
    {
        return $this->json(
            json_encode(
                $this->topicService->getAllPages($query->query->all())
            )
        );
    }
}