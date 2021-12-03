<?php

namespace App\Service;

use App\Entity\Post;
use App\Exception\PostIdNotFoundException;
use App\Exception\ValidatorDataSetException;
use App\Exception\ValidatorIdDoNotExists;
use App\Exception\ValidatorWrongIdException;
use App\Validator\CategoryValidator\CategoryContentValidator;
use App\Validator\CategoryValidator\CategoryCreatorValidator;
use App\Validator\CategoryValidator\CategoryTopicIdValidator;
use App\Validator\ValidatorDecorator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PostService implements CrudInterface
{
    public function __construct(private EntityManagerInterface $em, private NormalizerInterface $normalizer){}


    /**
     * @param array $data
     * @throws ValidatorDataSetException
     * @throws ValidatorWrongIdException
     * @throws ValidatorIdDoNotExists
     * @throws \App\Exception\ValidatorWrongTopicIdException
     */
    public function add(array $data)
    {
        $validator = (new ValidatorDecorator());
        $validator->setData($data);
        $validator = new CategoryCreatorValidator($validator);
        $validator = new CategoryContentValidator($validator);
        $validator = new CategoryTopicIdValidator($validator);
        $validator->setem($this->em);
        $validator->validate();

        unset($validator);

        $post = $this->normalizer->denormalize($data, post::class);

        $this->em->persist($post);

        $this->em->flush();
    }

    /**
     * @throws PostIdNotFoundException
     */
    public function delete(int $id)
    {
        /** @var Post $post */
        $post = $this->em->getRepository(post::class)
            ->findOneBy(["id" => $id, "isDeleted" => false]);
        if(!$post)
            throw new PostIdNotFoundException($id);
        $post->setIsDeleted(true);

        $this->em->persist($post);

        $this->em->flush();
    }

    /**
     * @param int $id
     * @param array $data
     * @throws ValidatorDataSetException
     * @throws ValidatorIdDoNotExists
     * @throws ValidatorWrongIdException
     */
    public function update(int $id, array $data)
    {
        $validator = (new ValidatorDecorator());
        $validator->setData($data);
        $validator = new CategoryCreatorValidator($validator);
        $validator->setem($this->em);
        $validator->validate();
    }

    public function get(int $id = null)
    {
        // TODO: Implement get() method.
    }
    public function getAll(): array
    {
        /** @var  Post[] $postList */
        $postList = $this->em->getRepository(post::class)
            ->findBy(['isDeleted' => false, 'isActive' => true]);


        $postListResponse = [];
        foreach ($postList as $post)
        {
            $postData = [
                "id" => $post->getId(),
                "topic" =>$post->getTopic(),
                "creatorID" => $post->getCreator(),
                "creationDate" => $post->getCreationDate()->format("Y-m-d H:i:s")
            ];
            $postListResponse[] = $postData;
        }
        return $postListResponse;
    }
}