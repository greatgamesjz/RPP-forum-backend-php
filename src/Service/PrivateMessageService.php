<?php

namespace App\Service;

use App\Entity\AppUser;
use App\Entity\PrivateMessage;
use App\Exception\PrivateMessageNotFoundException;
use App\Exception\UserNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PrivateMessageService implements CrudInterface
{
    public function __construct(private EntityManagerInterface $em, private NormalizerInterface $normalizer){}

    /**
     * @throws UserNotFoundException
     */
    public function add(array $data)
    {
        $sender = $this->em->getRepository(AppUser::class)->findOneBy(["id"=> $data["senderId"]]);
        if(!$sender)
            throw new UserNotFoundException($data["senderId"]);

        $reciver = $this->em->getRepository(AppUser::class)->findOneBy(["id"=> $data["reciverId"]]);
        if(!$reciver)
            throw new UserNotFoundException($data["reciverId"]);

        $privateMessage = $this->normalizer->denormalize($data, PrivateMessage::class);

        $privateMessage->setSender($sender);
        $privateMessage->setReciver($reciver);

        $this->em->persist($privateMessage);

        $this->em->flush();
    }

    /**
     * @throws PrivateMessageNotFoundException
     */
    public function delete(int $id)
    {
        /** @var PrivateMessage $privateMessage */
        $pm = $this->em->getRepository(PrivateMessage::class)
            ->findOneBy(["id" => $id, "isDeleted" => false]);
        if(!$pm)
            throw new PrivateMessageNotFoundException($id);
        $pm->setIsDeleted(true);

        $this->em->persist($pm);

        $this->em->flush();
    }

    /**
     * @throws PrivateMessageNotFoundException
     */
    public function update(int $id, array $data)
    {
        /** @var  PrivateMessage $pm */
        $pm = $this->em->getRepository(PrivateMessage::class)->findOneBy(["id" => $id]);
        if(!$pm)
            throw new PrivateMessageNotFoundException($id);

        $pm->setContent($data["content"]);

        $this->em->persist($pm);

        $this->em->flush();
    }

    /**
     * @throws PrivateMessageNotFoundException
     */
    public function get(int $id)
    {
        $pm = $this->em->getRepository(PrivateMessage::class)->findOneBy(["id" => $id]);
        if(!$pm)
            throw new PrivateMessageNotFoundException($id);
        return $pm;
    }
}