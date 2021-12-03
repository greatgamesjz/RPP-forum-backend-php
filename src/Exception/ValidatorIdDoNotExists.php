<?php

namespace App\Exception;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;

class ValidatorIdDoNotExists extends \Exception implements ValidatorExceptionInterface
{
    const MESSAGE = "User id: %s, does not exist.";

    #[Pure] public function __construct(string $field, $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, $field), $code, $previous);
    }
}