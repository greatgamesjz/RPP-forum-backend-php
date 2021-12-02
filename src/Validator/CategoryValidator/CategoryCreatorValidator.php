<?php

namespace App\Validator\CategoryValidator;

use App\Entity\AppUser;
use App\Validator\ValidatorDecorator;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\ValidatorWrongIdException;
use App\Exception\ValidatorIdDoNotExists;

class CategoryCreatorValidator extends ValidatorDecorator
{
    private EntityManagerInterface $entityManager;

    public function setem(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws ValidatorWrongIdException|ValidatorIdDoNotExists
     */
    public function validate()
    {
        $this->isNumeric(strval($this->data["id"]));
        $this->correctId(strval($this->data["id"]));
        $this->isAppUserExists(strval($this->data["id"]));
    }

    /**
     * @throws ValidatorWrongIdException
     */
    public function isNumeric($id): void
    {
        if(!is_numeric($id))
        {
            throw new ValidatorWrongIdException(strval($id));
        }
    }

    /**
     * @throws ValidatorWrongIdException
     */
    public function correctId($id): void
    {
           if(!$id>0)
           {
               throw new ValidatorWrongIdException(strval($id));
           }
    }

    /**
     * @throws ValidatorIdDoNotExists
     */
    public function isAppUserExists($id): void
    {
        if($this->entityManager->getRepository(appuser::class)->findOneBy(['id' => $id]) == null)
        {
            throw new ValidatorIdDoNotExists(strval($id));
        }
    }
}