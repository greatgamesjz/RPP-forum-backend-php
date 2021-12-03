<?php

namespace App\Service;

use App\Entity\Category;
use App\Exception\CategoryNotFoundException;
use App\Exception\ValidatorDataSetException;
use App\Exception\ValidatorWrongArgsCountException;
use App\Exception\ValidatorWrongCharacterCountException;
use App\Repository\CategoryRepository;
use App\Validator\UserValidator\UserEmailValidator;
use App\Validator\CategoryValidator\CategoryFieldsValidator;
use App\Validator\UserValidator\UserNicknameValidator;
use App\Validator\ValidatorDecorator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class CategoryService implements CrudInterface
{
    public function __construct(private EntityManagerInterface $em, private NormalizerInterface $normalizer)
    {
    }

    /**
     * @throws ValidatorDataSetException
     */
    public function add(array $data)
    {
        $validator = (new ValidatorDecorator());
        $validator->setData($data);
        $validator = new CategoryFieldsValidator($validator);
        $validator->validate();

        unset($validator);

        $category = $this->normalizer->denormalize($data, Category::class);

        $this->em->persist($category);

        $this->em->flush();
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function delete(int $id)
    {
        /** @var Category $cat */
        $cat = $this->em->getRepository(Category::class)
            ->findOneBy(["id" => $id, "isDeleted" => false]);
        if(!$cat)
            throw new CategoryNotFoundException($id);
        $cat->setIsDeleted(true);

        $this->em->persist($cat);

        $this->em->flush();
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function update(int $id, array $data)
    {
        /** @var  Category $cat */
        $cat = $this->em->getRepository(Category::class)->findOneBy(["id" => $id]);
        if(!$cat)
            throw new CategoryNotFoundException($id);

        $cat->setCategoryName($data["name"] ?? $cat->getCategoryName());
        $cat->setIsActive($data["isActive"] ?? $cat->getIsActive());

        $this->em->persist($cat);

        $this->em->flush();
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function get(int $id)
    {
        $cat = $this->em->getRepository(Category::class)->findOneBy(["id" => $id]);
        if(!$cat)
            throw new CategoryNotFoundException($id);
        return $cat;
    }

    public function getAll(): array
    {
        /** @var Category[] $catList */
        $catList = $this->em->getRepository(Category::class)
            ->findBy(['isDeleted' => false, 'isActive' => true]);

        $categoryListResponse = [];
        foreach($catList as $cat)
        {
            $catData = [
                "name" => $cat->getCategoryName(),
                "id" => $cat->getId(),
                "creationDate"=> $cat->getCreationDate()->format("Y-m-d H:i:s")
            ];
            $categoryListResponse[] = $catData;
        }

        return $categoryListResponse;
    }
}