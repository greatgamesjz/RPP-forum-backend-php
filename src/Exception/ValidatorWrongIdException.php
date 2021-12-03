<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Throwable;
use JetBrains\PhpStorm\Pure;

class ValidatorWrongIdException extends \Exception implements ValidatorExceptionInterface
{
    const MESSAGE = "%s must be a number and be greater than 0 ";

    #[Pure] public function __construct(string $field, $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, $field), $code, $previous);
    }
}