<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

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
     * @return Post[] Returns an array of Post objects
     */

    public function findByPages($maxResult, $page): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.isDeleted = :val')
            ->andWhere('t.isActive = :val2')
            ->setParameter('val', false)
            ->setParameter('val2', true)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults($maxResult)
            ->setFirstResult(($page-1)*$maxResult)
            ->getQuery()
            ->getResult()
            ;
    }

}
