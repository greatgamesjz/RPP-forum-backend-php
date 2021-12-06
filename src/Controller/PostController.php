<?php

namespace App\Controller;

use App\Exception\PostIdNotFoundException;
use App\Exception\ValidatorExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\PostService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    public function __construct(private PostService $postService){}

    /**
     * @Route("api/post/get/all", name="get_posts", methods={"GET"})
     */
    public function getPosts(): JsonResponse
    {
        return $this->json($this->postService->getAll());
    }

    /**
     * @Route("api/post/get/{id}", name="get_post", methods={"GET"})
     */
    public function getPost(int $id)
    {
        //TODO implement method
    }

    /**
     * @Route("api/post/add", name="add_post", methods={"POST"})
     */
    public function addPost(Request $request): JsonResponse
    {
        try{
            $this->postService->add($request->request->all());
        } catch (ValidatorExceptionInterface $e) {
            return $this->json($e->getMessage(), $e->getCode());
        } catch (\Exception $e){
            return $this->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->json("Success");
    }

    /**
     * @Route("api/post/delete/{id}", name="delete_post", methods={"GET"})
     */
    public function deletePost(int $id): JsonResponse
    {
        try{
            $this->postService->delete($id);
        }catch (PostIdNotFoundException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("Success");
    }
}