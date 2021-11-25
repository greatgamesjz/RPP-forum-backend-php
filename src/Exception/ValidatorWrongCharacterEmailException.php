<?php

namespace App\Exception;

use App\Validator\CategoryValidator\CategoryEmailValidator;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;

class ValidatorWrongCharacterEmailException extends \Exception implements ValidatorExceptionInterface
{
    const MESSAGE = "%s must be at least %d characters in length and must contain @.";

    #[Pure] public function __construct(string $field, $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, $field, CategoryEmailValidator::MIN_LENGTH,), $code, $previous);
    }
}