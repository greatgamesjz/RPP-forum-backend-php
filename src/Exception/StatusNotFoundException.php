<?php

namespace App\Exception;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;

class StatusNotFoundException extends \Exception
{
    const MESSAGE = "Status ID: %d does not exist";
    #[Pure] public function __construct(int $id, int $code = Response::HTTP_BAD_REQUEST)
    {
        $message = sprintf(self::MESSAGE, $id);
        parent::__construct($message, $code);
    }
}