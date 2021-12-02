<?php

namespace App\Service;

use App\Exception\ValidatorDataSetException;
use App\Exception\ValidatorIdDoNotExists;
use App\Exception\ValidatorWrongIdException;
use App\Validator\CategoryValidator\CategoryCreatorValidator;
use App\Validator\ValidatorDecorator;
use Doctrine\ORM\EntityManagerInterface;

class PostService implements CrudInterface
{
    public function __construct(private EntityManagerInterface $em){}


    /**
     * @param array $data
     * @throws ValidatorDataSetException
     * @throws ValidatorWrongIdException
     * @throws ValidatorIdDoNotExists
     */
    public function add(array $data)
    {
        $validator = (new ValidatorDecorator());
        $validator->setData($data);
        $validator = new CategoryCreatorValidator($validator);
        $validator->setem($this->em);
        $validator->validate();
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param int $id
     * @param array $data
     * @throws ValidatorDataSetException
     * @throws ValidatorIdDoNotExists
     * @throws ValidatorWrongIdException
     */
    public function update(int $id, array $data)
    {
        $validator = (new ValidatorDecorator());
        $validator->setData($data);
        $validator = new CategoryCreatorValidator($validator);
        $validator->setem($this->em);
        $validator->validate();
    }

    public function get(int $id = null)
    {
        // TODO: Implement get() method.
    }
}