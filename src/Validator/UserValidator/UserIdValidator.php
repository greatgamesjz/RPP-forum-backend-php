<?php

namespace App\Validator\UserValidator;

use App\Entity\AppUser;
use App\Exception\ValidatorIdDoNotExists;
use App\Validator\ValidatorDecorator;
use Doctrine\ORM\EntityManagerInterface;

class UserIdValidator extends ValidatorDecorator
{
    private EntityManagerInterface $entityManager;

    public function setem(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws ValidatorIdDoNotExists
     */
    public function validate()
    {
        $this->validateId(intval($this->data["id"]));
        parent::validate();
    }

    /**
     * @throws ValidatorIdDoNotExists
     */
    private function validateId($id)
    {
        if (!$this->entityManager->getRepository(appuser::class)->findOneBy(['id' => $id]))
            throw new ValidatorIdDoNotExists($id);
    }
}