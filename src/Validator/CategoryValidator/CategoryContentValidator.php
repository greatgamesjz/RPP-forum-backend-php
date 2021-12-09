<?php

namespace App\Validator\CategoryValidator;

use App\Validator\ValidatorDecorator;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\ValidatorMinLengthContentException;
use App\Exception\ValidatorIndecentWordsException;

class CategoryContentValidator extends ValidatorDecorator
{
    const MIN_LENGTH = 5;
    const INDECENTWORDS = ["dariusz","kurwa"];
    private EntityManagerInterface $entityManager;

    public function setem(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws ValidatorMinLengthContentException|ValidatorIndecentWordsException
     */
    public function validate()
    {
        $this->minLength(strval($this->data["content"]));
        $this->indecentWords();
        parent::validate();
    }

    /**
     * @throws ValidatorMinLengthContentException
     */
    private function minLength(string $content): void
    {
        if(strlen($content)<self::MIN_LENGTH)
        {
            throw new ValidatorMinLengthContentException("Content");
        }
    }

    /**
     * @throws ValidatorIndecentWordsException
     */
    private function indecentWords(): void
    {
        foreach (self::INDECENTWORDS as $field)
        {
            //dd($this->data);
            if(in_array($field,$this->data))
                throw new ValidatorIndecentWordsException();
        }
    }

}