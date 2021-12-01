<?php

namespace App\Validator\UserValidator;

use App\Exception\ValidatorWrongCharacterPasswordException;
use App\Validator\ValidatorDecorator;

class UserPasswordValidator extends ValidatorDecorator
{
    const MIN_LENGTH = 6;
    /**
     * @throws ValidatorWrongCharacterPasswordException
     */
    public function validate()
    {

        $this->validatePasswordLength();
        parent::validate();
    }

    /**
     * @throws ValidatorWrongCharacterPasswordException
     */
    private function validatePasswordLength(): void
    {
        if (strlen($this->data["password"]) < self::MIN_LENGTH ||
            !preg_match('@[0-9]@', $this->data["password"]) ||
            !preg_match('@[a-z]@', $this->data["password"]) ||
            !preg_match('@[A-Z]@', $this->data["password"]) ||
            !preg_match('/[!@#$%^&*()]/', $this->data["password"])) {
            throw new ValidatorWrongCharacterPasswordException("password");
        }
    }
}