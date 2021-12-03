<?php

namespace App\Exception;

use App\Validator\CategoryValidator\CategoryContentValidator;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;

class ValidatorMinLengthContentException extends \Exception implements ValidatorExceptionInterface
{
    const MESSAGE = "%s too short, min %d .";

    #[Pure] public function __construct(string $field, $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, $field, CategoryContentValidator::MIN_LENGTH,), $code, $previous);
    }
}