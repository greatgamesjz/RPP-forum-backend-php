<?php

namespace App\Exception;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ValidatorDataSetException extends \Exception implements ValidatorExceptionInterface
{
    const MESSAGE = "Validator data already set";
    #[Pure] public function __construct($message = self::MESSAGE, $code = Response::HTTP_INTERNAL_SERVER_ERROR, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}