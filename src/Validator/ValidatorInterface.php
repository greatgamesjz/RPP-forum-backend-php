<?php

namespace App\Validator;

interface ValidatorInterface
{
    function setData(array $data);
    function validate();
}