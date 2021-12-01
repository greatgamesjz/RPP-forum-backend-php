<?php

namespace App\Validator\UserValidator;

use App\Exception\ValidatorWrongCharacterCountException;
use App\Validator\ValidatorDecorator;

class UserNicknameValidator extends ValidatorDecorator
{
    const MIN_LENGTH = 5;
    const MAX_LENGTH = 20;

    /**
     * @throws ValidatorWrongCharacterCountException
     */
    public function validate()
    {

        $this->validateNickNameLength();
        parent::validate();
    }

    /**
     * @throws ValidatorWrongCharacterCountException
     */
    private function validateNickNameLength(): void
    {
        if(strlen($this->data["nickname"]) < self::MIN_LENGTH || strlen($this->data["nickname"]) > self::MAX_LENGTH) {
            throw new ValidatorWrongCharacterCountException("nickname");
        }

    }
}