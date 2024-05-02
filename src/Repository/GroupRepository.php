<?php

namespace App\Repository;

use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Group>
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }
    
    public function findGroupByUser($user): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.leader = :user')
            ->setParameter('user', $user)
            ->orderBy('g.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
