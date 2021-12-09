<?php

namespace App\Validator\CategoryValidator;

use App\Entity\Topic;
use App\Exception\ValidatorWrongArgsCountException;
use App\Exception\ValidatorWrongTopicIdException;
use App\Validator\ValidatorDecorator;
use Doctrine\ORM\EntityManagerInterface;

class CategoryTopicIdValidator extends ValidatorDecorator
{
    private EntityManagerInterface $entityManager;
    const WHITELISTED_FIELDS = ["creator", "topic", "content"];

    public function setem(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws ValidatorWrongTopicIdException|ValidatorWrongArgsCountException
     */
    public function validate()
    {
        $this->validateTopicId(intval($this->data["topic"]));
        $this->validateFieldsExists();
        $this->validateFieldsNotExists();
        parent::validate();
    }

    /**
     * @throws ValidatorWrongTopicIdException
     */
    private function validateTopicId(int $id)
    {
        if(!$this->entityManager->getRepository(topic::class)->findOneBy(['id' => $id]))
            throw new ValidatorWrongTopicIdException($id);
    }

    /**
     * @throws ValidatorWrongArgsCountException
     */
    private function validateFieldsExists() {
        foreach (self::WHITELISTED_FIELDS as $field) {
            if(!array_key_exists($field, $this->data))
                throw new ValidatorWrongArgsCountException();
        }

    }

    /**
     * @throws ValidatorWrongArgsCountException
     */
    private function validateFieldsNotExists() {
        foreach (array_keys($this->data) as $field) {
            if(!in_array($field, self::WHITELISTED_FIELDS))
                throw new ValidatorWrongArgsCountException();
        }
    }
}