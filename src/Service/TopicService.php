<?php

namespace App\Service;

use App\Entity\Topic;
use App\Exception\CategoryNotFoundException;
use App\Exception\TopicNotFoundException;
use App\Exception\ValidatorDataSetException;
use App\Exception\ValidatorWrongArgsCountException;
use App\Exception\ValidatorWrongCharacterCountException;
use App\Validator\CategoryValidator\CategoryFieldsValidator;
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
     */
    public function add(array $data)
    {

        $validator = (new ValidatorDecorator());
        $validator->setData($data);
        $validator = new CategoryFieldsValidator($validator);
        $validator->validate();
        unset($validator);

        $topic = $this->normalizer->denormalize($data, Topic::class);

        $this->em->persist($topic);

        $this->em->flush();
    }

    /**
     * @throws TopicNotFoundException
     */
    public function delete(int $id)
    {
        /** @var Topic $top */
        $top = $this->em->getRepository(Topic::class)
            ->findOneBy(["id" => $id, "isDeleted" => false]);
        if(!$top)
            throw new TopicNotFoundException($id);
        $top->setIsDeleted(true);

        $this->em->persist($top);

        $this->em->flush();
    }

    /**
     * @throws TopicNotFoundException
     */
    public function update(int $id, array $data)
    {
        /** @var  Topic $top */
        $top = $this->em->getRepository(Topic::class)->findOneBy(["id" => $id]);
        if(!$top)
            throw new TopicNotFoundException($id);

        $top->setTopicName($data["topicName"] ?? $top->getTopicName());
        $top->setCategory($data["category"] ?? $top->getCategory());
        $top->setIsActive($data["isActive"] ?? $top->getIsActive());

        $this->em->persist($top);

        $this->em->flush();
    }

    /**
     * @throws TopicNotFoundException
     */
    public function get(int $id)
    {
        $top = $this->em->getRepository(Topic::class)->findOneBy(["id" => $id]);
        if(!$top)
            throw new TopicNotFoundException($id);
        return $top;
    }

    public function getAll(): array
    {
        /** @var Topic[] $topList */
        $topList = $this->em->getRepository(Topic::class)
            ->findBy(['isDeleted' => false, 'isActive' => true]);

        $topicListResponse = [];
        foreach($topList as $top)
        {
            $topData = [
                "name" => $top->getTopicName(),
                "id" => $top->getId(),
                "creationDate"=> $top->getCreationDate()->format("Y-m-d H:i:s")
            ];
            $topicListResponse[] = $topData;
        }

        return $topicListResponse;
    }
}