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

class CategoryController extends AbstractController
{
    public function __construct(private CategoryService $categoryService){}

    /**
     * @Route("/api/category/get/all", name="get_categories", methods={"GET"})
     */
    public function getCategories(): JsonResponse
    {
        return $this->json($this->categoryService->getAll());
    }

    /**
     * @Route("/api/category/get/{id}", name="get_category", methods={"GET"})
     */
    public function getCategory(int $id)
    {
        try {
            return $this->json(json_encode(($this->categoryService->get($id))));
        } catch (CategoryNotFoundException $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Route("/api/category/add", name="add_category", methods={"POST"})
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
     */
    public function deleteCategory(int $id)
    {
        try {
            $this->categoryService->delete($id);
        } catch (CategoryNotFoundException $e){
            return $this->json($e->getMessage(), $e->getCode());
        }
        return $this->json("SUCCES");
    }
}