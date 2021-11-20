<?php

namespace App\Validator;

use App\Exception\ValidatorDataSetException;

class ValidatorDecorator implements ValidatorInterface
{
    protected ?array $data;

    public function __construct(protected ?ValidatorInterface $validator = null)
    {
        $this->data = $this->validator?->data;
    }


    public function validate() {
        $this->validator?->validate();
    }

    /**
     * @throws ValidatorDataSetException
     */
    function setData(array $data): static
    {
        if($this->data != null)
            throw new ValidatorDataSetException();
        $this->data = $data;

        return $this;
    }
}