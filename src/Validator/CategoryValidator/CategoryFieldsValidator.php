<?php

namespace App\Validator\CategoryValidator;

use App\Exception\ValidatorWrongArgsCountException;
use App\Validator\ValidatorDecorator;

class CategoryFieldsValidator extends ValidatorDecorator
{
    const WHITELISTED_FIELDS = ["name","creator"];

    /**
     * @throws ValidatorWrongArgsCountException
     */
    public function validate()
    {
        $this->validateFieldsExists();
        $this->validateFieldsNotExists();
        parent::validate();
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