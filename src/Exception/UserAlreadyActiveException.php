<?php

namespace App\Exception;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserAlreadyActiveException extends \Exception
{
    const MESSAGE = "User is already active or does not exist.";
    #[Pure] public function __construct(int $code = Response::HTTP_ALREADY_REPORTED)
    {
        $message = self::MESSAGE;
        parent::__construct($message, $code);
    }
}