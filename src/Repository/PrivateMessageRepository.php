<?php

namespace App\Repository;

use App\Entity\PrivateMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method PrivateMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrivateMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrivateMessage[]    findAll()
 * @method PrivateMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrivateMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrivateMessage::class);
    }

    // /**
    //  * @return PrivateMessage[] Returns an array of PrivateMessage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    /**
     * @return PrivateMessage[] Returns an array of PrivateMessage objects
     */

    public function findByPages($maxResult, $page, $id): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.sender = :val')
            ->andWhere('t.isDeleted = :val2')
            ->orWhere('t.reciver = :val')
            ->andWhere('t.isDeleted = :val2')
            ->setParameter('val', $id)
            ->setParameter('val2', false)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults($maxResult)
            ->setFirstResult(($page-1)*$maxResult)
            ->getQuery()
            ->getResult()
            ;
    }
}
