<?php

namespace App\Validator\CategoryValidator;

use App\Entity\Category;
use App\Exception\ValidatorWrongCharacterEmailException;
use App\Normalizer\EntityNormalizer;
use App\Repository\CategoryRepository;
use App\Validator\ValidatorDecorator;

class CategoryEmailValidator extends ValidatorDecorator
{
    const MIN_LENGTH = 5;
    private CategoryRepository $categoryRepository;
    /**
     * @throws ValidatorWrongCharacterEmailException
     */
    public function validate()
    {

        $this->validateEmailLength();
        $this->checkIfEmExists();
        $this->isEmailUnique();
        parent::validate();
    }

    /**
     * @param EntityManagerInterface $em
     */
    public function setEm(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }

    /**
     * @throws ValidatorWrongCharacterEmailException
     */
    private function validateEmailLength(): void
    {
        if(strlen($this->data["email"]) < self::MIN_LENGTH ||
            !strpos($this->data["email"],"@")) {
            throw new ValidatorWrongCharacterEmailException("email");
            // TODO UNIKALNY EMAIL
        }
    }
}