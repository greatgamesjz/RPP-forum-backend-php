<?php

namespace App\Exception;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;

class ValidatorEmaiIExistsException extends \Exception implements ValidatorExceptionInterface
{
    const MESSAGE = "%s is already used.";

    #[Pure] public function __construct(string $field, $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, $field), $code, $previous);
    }

}