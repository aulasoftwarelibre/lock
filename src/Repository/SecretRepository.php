<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Secret;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Secret|null find($id, $lockMode = null, $lockVersion = null)
 * @method Secret|null findOneBy(array $criteria, array $orderBy = null)
 * @method Secret[]    findAll()
 * @method Secret[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecretRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Secret::class);
    }

    public function userHasAccessToSecret(Secret $secret, User $user): bool
    {
        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.organizations', 'p')
            ->leftJoin('p.members', 'm')
            ->where('s = :secret')
            ->andWhere('m = :member')
            ->setParameter('secret', $secret)
            ->setParameter('member', $user);

        $result = $qb
            ->getQuery()
            ->getOneOrNullResult();

        return $result instanceof Secret;
    }

    // /**
    //  * @return Secret[] Returns an array of Secret objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Secret
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
