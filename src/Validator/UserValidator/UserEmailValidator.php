<?php

namespace App\Validator\UserValidator;

use App\Entity\AppUser;
use App\Exception\ValidatorWrongCharacterEmailException;
use App\Exception\ValidatorEmaiIExistsException;
use App\Validator\ValidatorDecorator;
use Doctrine\ORM\EntityManagerInterface;

class UserEmailValidator extends ValidatorDecorator
{
    const MIN_LENGTH = 5;
    private EntityManagerInterface $entityManager;

    public function setem(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws ValidatorWrongCharacterEmailException
     */
    public function validate()
    {
        $this->validateEmailLength(strval($this->data["email"]));
        $this->checkIfEmailExists(strval($this->data["email"]));
        parent::validate();
    }

    /**
     * @throws ValidatorWrongCharacterEmailException
     */
    private function validateEmailLength($email): void
    {
        if($email < self::MIN_LENGTH ||
            !strpos($email,"@")) {
            throw new ValidatorWrongCharacterEmailException("email");
        }
    }
    /**
     * @throws ValidatorEmaiIExistsException
     */
    public function checkIfEmailExists($email)
    {
        if($this->entityManager->getRepository(appuser::class)->findOneBy(['email' => $email]) !== null)
        {
            throw new ValidatorEmaiIExistsException(strval($email));
        }
    }
}