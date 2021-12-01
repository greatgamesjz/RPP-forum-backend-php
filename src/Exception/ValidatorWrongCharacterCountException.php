<?php

namespace App\Exception;

use App\Validator\UserValidator\UserNicknameValidator;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ValidatorWrongCharacterCountException extends \Exception implements ValidatorExceptionInterface
{
    const MESSAGE = "%s must have more than %d characters and less than %d";
    #[Pure] public function __construct(string $field, $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, $field, UserNicknameValidator::MIN_LENGTH, UserNicknameValidator::MAX_LENGTH), $code, $previous);
    }
}