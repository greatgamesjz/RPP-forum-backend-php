<?php

namespace App\Exception;

use App\Validator\CategoryValidator\CategoryFieldsValidator;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ValidatorWrongArgsCountException extends \Exception implements ValidatorExceptionInterface
{
    const MESSAGE = "Your request must have only following args: %s";
    #[Pure] public function __construct($code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, implode(" ", CategoryFieldsValidator::WHITELISTED_FIELDS)), $code, $previous);
    }
}