<?php

namespace App\Exception;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;

class ValidatorWrongTopicIdException extends \Exception implements ValidatorExceptionInterface
{
    const MESSAGE = "%s does not exists.";

    #[Pure] public function __construct(string $field, $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, $field), $code, $previous);
    }
}