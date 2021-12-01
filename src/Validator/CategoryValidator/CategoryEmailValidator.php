<?php

namespace App\Validator\CategoryValidator;

use App\Entity\AppUser;
use App\Exception\ValidatorWrongCharacterEmailException;
use App\Exception\ValidatorEmailIsExistsException;
use App\Repository\CategoryEmailRepository;
use App\Validator\ValidatorDecorator;
use Doctrine\ORM\EntityManagerInterface;

class CategoryEmailValidator extends ValidatorDecorator
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
        $this->validateEmailLength();
        parent::validate();
    }

    /**
     * @throws ValidatorWrongCharacterEmailException
     */
    private function validateEmailLength(): void
    {
        if(strlen($this->data["email"]) < self::MIN_LENGTH ||
            !strpos($this->data["email"],"@")) {
            throw new ValidatorWrongCharacterEmailException("email");
        }
        $this->checkIfEmailExists(strval($this->data["email"]));
    }
    /**
     * @throws ValidatorEmailIsExistsException
     */
    public function checkIfEmailExists($email)
    {
        if($this->entityManager->getRepository(appuser::class)->findOneBy(['email' => $email]) !== null)
        {
            throw new ValidatorEmailIsExistsException(strval($email));
        }
    }
}