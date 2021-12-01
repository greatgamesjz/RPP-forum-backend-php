<?php

namespace App\Exception;

use App\Validator\UserValidator\UserEmailValidator;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;

class ValidatorWrongCharacterEmailException extends \Exception implements ValidatorExceptionInterface
{
    const MESSAGE = "%s must be at least %d characters in length and must contain @.";

    #[Pure] public function __construct(string $field, $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, $field, UserEmailValidator::MIN_LENGTH,), $code, $previous);
    }
}