<?php

namespace App\Exception;

use App\Validator\CategoryValidator\CategoryPasswordValidator;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;

class ValidatorWrongCharacterPasswordException extends \Exception implements ValidatorExceptionInterface
{
    const MESSAGE = "%s must be at least %d characters in length and must contain at least one number, one upper case letter, one lower case letter and one special character.";
    #[Pure] public function __construct(string $field, $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, $field, CategoryPasswordValidator::MIN_LENGTH,), $code, $previous);
    }
}