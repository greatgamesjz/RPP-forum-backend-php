<?php

namespace App\Validator\CategoryValidator;

use App\Exception\ValidatorWrongCharacterCountException;
use App\Validator\ValidatorDecorator;

class CategoryNameValidator extends ValidatorDecorator
{
    const MIN_LENGTH = 5;
    const MAX_LENGTH = 20;

    /**
     * @throws ValidatorWrongCharacterCountException
     */
    public function validate()
    {

        $this->validateNameLength();
        parent::validate();
    }

    /**
     * @throws ValidatorWrongCharacterCountException
     */
    private function validateNameLength(): void
    {
        if(strlen($this->data["name"]) < self::MIN_LENGTH || strlen($this->data["name"]) > self::MAX_LENGTH) {
            throw new ValidatorWrongCharacterCountException("name");
        }

    }
}