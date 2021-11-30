<?php

namespace App\Controller;

use App\Exception\ValidatorExceptionInterface;
use App\Service\TopicService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class TopicController extends AbstractController
{
    public function __construct(private TopicService $topicService){}

    /**
     * @Route("/api/topics", name="get_topics", methods={"GET"})
     */
    public function getTopics()
    {
        //@TODO implement method
    }
    /**
     * @Route("/api/topics/{id}", name="get_topic", methods={"GET"})
     */
    public function getTopic(int $id)
    {
        //@TODO implement method
    }
    /**
     * @Route("/api/topic", name="add_topic", methods={"POST"})
     */
    public function addTopic(Request $request): Response
    {

        try {
            $this->topicService->add($request->request->all());
        } catch (ValidatorExceptionInterface $e) {
            return $this->json($e->getMessage(), $e->getCode());
        } catch (\Exception|ExceptionInterface $e) {
            return $this->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->json("Success");
    }
}