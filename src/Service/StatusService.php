<?php

namespace App\Service;


use App\Entity\AppUser;
use App\Entity\Status;
use App\Exception\StatusNotFoundException;
use App\Exception\UserNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class StatusService implements CrudInterface
{
    public function __construct(private EntityManagerInterface $em, private NormalizerInterface $normalizer)
    {
    }

    /**
     * @throws UserNotFoundException
     */
    public function add(array $data)
    {
        $user = $this->em->getRepository(AppUser::class)->findOneBy(["id" => $data["userId"]]);
        if(!$user)
            throw new UserNotFoundException($data["userId"]);

        $status = $this->normalizer->denormalize($data, Status::class);

        $status->setUser($user);

        $this->em->persist($status);

        $this->em->flush();
    }

    /**
     * @throws StatusNotFoundException
     */
    public function delete(int $id)
    {
        /** @var Status $status */
        $status = $this->em->getRepository(Status::class)
            ->findOneBy(["id" => $id, "isDeleted" => false]);
        if(!$status)
            throw new StatusNotFoundException($id);
        $status->setIsDeleted(true);

        $this->em->persist($status);

        $this->em->flush();
    }

    /**
     * @throws StatusNotFoundException
     */
    public function update(int $id, array $data)
    {
        /** @var  Status $status */
        $status = $this->em->getRepository(Status::class)->findOneBy(["id" => $id]);
        if(!$status)
            throw new StatusNotFoundException($id);

        $status->setContent($data["content"] ?? $status->getContent());
        $status->setCreationDate(new \DateTime());

        $this->em->persist($status);

        $this->em->flush();
    }

    /**
     * @throws StatusNotFoundException
     */
    public function get(int $id)
    {
        $status = $this->em->getRepository(Status::class)->findOneBy(["id" => $id, "isDeleted" => false]);
        if(!$status)
            throw new StatusNotFoundException($id);

        return $status;
    }

    public function getFive(): array
    {

        /** @var Status[] $statusList */
        $statusList = $this->em->getRepository(Status::class)
            ->findBy(['isDeleted' => false], ['creationDate'=> 'DESC'] , 5);

        $statusListResponse = [];
        foreach($statusList as $stat)
        {
            $statData = [
                "content" => $stat->getContent(),
                "id" => $stat->getId(),
                "creationDate"=> $stat->getCreationDate()->format("Y-m-d H:i:s"),
                "user"=> $stat->getUser()->getId()
            ];
            $statusListResponse[] = $statData;
        }

        return $statusListResponse;
    }
}