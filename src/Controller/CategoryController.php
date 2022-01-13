<?php

namespace App\Controller;

use App\Exception\CategoryNotFoundException;
use App\Exception\ValidatorDataSetException;
use App\Exception\ValidatorExceptionInterface;
use App\Exception\ValidatorWrongArgsCountException;
use App\Exception\ValidatorWrongCharacterCountException;
use App\Service\CategoryService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use OpenApi\Annotations as OA;

class CategoryController extends AbstractController
{
    public function __construct(private CategoryService $categoryService){}

    /**
     * @Route("/api/category/get/all", name="get_categories", methods={"GET"})
     * @OA\Response (response=200, description="Zwraca wszystkie kategorie.")
     * @OA\Tag (name="Category")
     */
    public function getCategories(): JsonResponse
    {
        return $this->json($this->categoryService->getAll());
    }

    /**
     * @Route("/api/category/get/{id}", name="get_category", methods={"GET"}, requirements={"id"="^[0-9]*$"})
     * @OA\Response (response=200, description="Zwraca kategorię.")
     * @OA\Tag (name="Category")
     */
    public function getCategory(int $id): JsonResponse
    {
        try {
            return $this->json(json_encode($this->categoryService->get($id)));
        } catch (CategoryNotFoundException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Route("/api/category/add", name="add_category", methods={"POST"})
     * @OA\Response (response=200, description="Dodaje kategorię.")
     * @OA\Tag (name="Category")
     */
    public function addCategory(Request $request): JsonResponse
    {
        try {
            $this->categoryService->add($request->request->all());
        } catch (ValidatorExceptionInterface $e) {
            return $this->json($e->getMessage(), $e->getCode());
        } catch (\Exception|ExceptionInterface $e) {
            return $this->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->json("Success");
    }

    /**
     * @Route("/api/category/delete/{id}", name="delete_category", methods={"DELETE"})
     * @OA\Response (response=200, description="Usuwa kategorię.")
     * @OA\Tag (name="Category")
     */
    public function deleteCategory(int $id) : JsonResponse
    {
        try {
            $this->categoryService->delete($id);
        } catch (CategoryNotFoundException $e){
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("SUCCES");
    }

    /**
     * @Route("/api/category/update/{id}", name="update_category", methods={"PATCH"})
     * @OA\Response (response=200, description="Edytuje kategorię.")
     * @OA\Tag (name="Category")
     */
    public function updateCategory(Request $request, int $id) : JsonResponse
    {
        try {
            $this->categoryService->update($id, $request->request->all());
        } catch (CategoryNotFoundException $e){
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("SUCCES");
    }
}