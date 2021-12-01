<?php

namespace App\Exception;

use App\Validator\CategoryValidator\CategoryEmailValidator;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;

class ValidatorEmailIsExistsException extends \Exception implements ValidatorExceptionInterface
{
    const MESSAGE = "%s is already used.";

    #[Pure] public function __construct(string $field, $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, $field), $code, $previous);
    }

}