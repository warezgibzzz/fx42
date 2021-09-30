<?php

namespace App\Repository;

use App\Entity\GuildMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GuildMember|null find($id, $lockMode = null, $lockVersion = null)
 * @method GuildMember|null findOneBy(array $criteria, array $orderBy = null)
 * @method GuildMember[]    findAll()
 * @method GuildMember[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuildMemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuildMember::class);
    }

    // /**
    //  * @return GuildMember[] Returns an array of GuildMember objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GuildMember
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
