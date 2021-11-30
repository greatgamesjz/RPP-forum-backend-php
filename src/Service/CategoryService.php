<?php

namespace App\Service;

use App\Entity\Category;
use App\Exception\ValidatorDataSetException;
use App\Exception\ValidatorWrongArgsCountException;
use App\Exception\ValidatorWrongCharacterCountException;
use App\Repository\CategoryRepository;
use App\Validator\CategoryValidator\CategoryEmailValidator;
use App\Validator\CategoryValidator\CategoryFieldsValidator;
use App\Validator\CategoryValidator\CategoryNameValidator;
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
     * @throws ValidatorWrongArgsCountException
     * @throws ValidatorWrongCharacterCountException
     */
    public function add(array $data)
    {
        $validator = (new ValidatorDecorator());
        $validator->setData($data);
        $validator = new CategoryNameValidator($validator);
        $validator = new CategoryFieldsValidator($validator);
        $validator = new CategoryEmailValidator($validator);
        $validator->setEm($this->em->getRepository(Category::class));
        $validator->validate();

        unset($validator);

        $category = $this->normalizer->denormalize($data, Category::class);

        $this->em->persist($category);

        $this->em->flush();
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }

    public function update(int $id, array $data)
    {
        // TODO: Implement update() method.
    }

    public function get(int $id = null)
    {
        // TODO: Implement get() method.
    }
}