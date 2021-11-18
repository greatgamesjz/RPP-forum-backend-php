<?php

namespace App\Exception;

use App\Validator\CategoryValidator\CategoryFieldsValidator;
use App\Validator\CategoryValidator\CategoryNameValidator;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ValidatorWrongCharacterCountException extends \Exception implements ValidatorExceptionInterface
{
    const MESSAGE = "%s must have more than %d characters and less than %d";
    #[Pure] public function __construct(string $field, $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, $field, CategoryNameValidator::MIN_LENGTH, CategoryNameValidator::MAX_LENGTH), $code, $previous);
    }
}