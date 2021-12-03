<?php

namespace App\Validator\CategoryValidator;

use App\Entity\Topic;
use App\Exception\ValidatorWrongTopicIdException;
use App\Validator\ValidatorDecorator;
use Doctrine\ORM\EntityManagerInterface;

class CategoryTopicIdValidator extends ValidatorDecorator
{
    private EntityManagerInterface $entityManager;

    public function setem(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws ValidatorWrongTopicIdException
     */
    public function validate()
    {
        $this->validateTopicId(intval($this->data["id"]));
        parent::validate();
    }

    /**
     * @throws ValidatorWrongTopicIdException
     */
    public function validateTopicId(int $id)
    {
        if(!$this->entityManager->getRepository(topic::class)->findOneBy(['id' => $id]))
            throw new ValidatorWrongTopicIdException($id);
    }
}