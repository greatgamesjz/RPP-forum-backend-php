<?php

namespace App\Validator\CategoryValidator;

use App\Exception\ValidatorWrongCharacterEmailException;
use App\Validator\ValidatorDecorator;

class CategoryEmailValidator extends ValidatorDecorator
{
    const MIN_LENGTH = 5;

    /**
     * @throws ValidatorWrongCharacterEmailException
     */
    public function validate()
    {

        $this->validateEmailLength();
        parent::validate();
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