<?php

namespace App\Service;

use App\Entity\Topic;
use App\Exception\ValidatorDataSetException;
use App\Exception\ValidatorWrongArgsCountException;
use App\Exception\ValidatorWrongCharacterCountException;
use App\Validator\CategoryValidator\CategoryFieldsValidator;
use App\Validator\CategoryValidator\CategoryNameValidator;
use App\Validator\ValidatorDecorator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TopicService implements CrudInterface
{
    public function __construct(private EntityManagerInterface $em, private NormalizerInterface $normalizer)
    {
    }

    /**
     * @throws ValidatorDataSetException
     * @throws ValidatorWrongArgsCountException
     * @throws ValidatorWrongCharacterCountException
     */
    public function add(array $data)
    {

        $validator = (new ValidatorDecorator());
        $validator->setData($data);
        $validator = new CategoryNameValidator($validator);
        $validator = new CategoryFieldsValidator($validator);
        $validator->validate();

        unset($validator);

        $topic = $this->normalizer->denormalize($data, Topic::class);

        $this->em->persist($topic);

        $this->em->flush();
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }

    public function update(int $id, array $data)
    {
        // TODO: Implement update() method.
    }

    public function get(int $id = null)
    {
        // TODO: Implement get() method.
    }
}