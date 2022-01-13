<?php

namespace App\Controller;

use App\Exception\PostIdNotFoundException;
use App\Exception\UserNotFoundException;
use App\Exception\ValidatorDataSetException;
use App\Exception\ValidatorExceptionInterface;
use App\Exception\ValidatorIdDoNotExists;
use App\Exception\ValidatorWrongIdException;
use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\PostService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class PostController extends AbstractController
{
    public function __construct(private PostService $postService,
                                private AuthService $authService){}

    /**
     * @Route("api/post/get/all", name="get_posts", methods={"GET"})
     * @OA\Response (response=200, description="Zwraca wszystkie posty.")
     * @OA\Tag (name="Post")
     */
    public function getPosts(): JsonResponse
    {
        return $this->json($this->postService->getAll());
    }

    /**
     * @Route("api/post/get/{id}", name="get_post", methods={"GET"}, requirements={"id"="^[0-9]*$"})
     * @OA\Response (response=200, description="Zwraca post.")
     * @OA\Tag (name="Post")
     */
    public function getPost(int $id)
    {
        //TODO implement method
    }

    /**
     * @Route("api/post/add", name="add_post", methods={"POST"})
     * @OA\Response (response=200, description="Dodaje post.")
     * @OA\Tag (name="Post")
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
     * @OA\Response (response=200, description="Usuwa post.")
     * @OA\Tag (name="Post")
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

    /**
     * @Route("api/post/update/{id}", name="update_post", methods={"POST"})
     * @OA\Response (response=200, description="Edytuje post.")
     * @OA\Tag (name="Post")
     */
    public function updatePost(Request $request, int $id): JsonResponse
    {
        try {
            $this->postService->update($id, $request->request->all());
        } catch (PostIdNotFoundException|ValidatorDataSetException|
        ValidatorIdDoNotExists|ValidatorWrongIdException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("Success");
    }

    /**
     * @Route("/api/like", name="like", methods={"POST"})
     * @OA\Response (response=200, description="Dodaje polubienie do postu.")
     * @OA\Tag (name="Post")
     */
    public function likePost(Request $request): JsonResponse{
        try {
            $this->authService->isAuthorized($request->request->get("userId"));
            $this->postService->likePost(
                $request->request->get("postId"),
                $request->request->get("userId")
            );
        }catch(AccessDeniedException|PostIdNotFoundException $e){
            return $this->json($e->getMessage(), RESPONSE::HTTP_BAD_REQUEST | $e->getCode());
        }catch(UserNotFoundException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("Success");
    }
}