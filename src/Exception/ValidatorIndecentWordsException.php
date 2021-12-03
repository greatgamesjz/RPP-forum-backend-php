<?php

namespace App\Exception;

use App\Validator\CategoryValidator\CategoryContentValidator;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;

class ValidatorIndecentWordsException extends \Exception implements ValidatorExceptionInterface
{
    const MESSAGE = "Indecent words in content!.";

    #[Pure] public function __construct($code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, implode(" ", CategoryContentValidator::INDECENTWORDS)), $code, $previous);
    }
}