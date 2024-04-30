<?php

namespace App\Repository;

use App\Entity\Activity;
use App\Entity\SearchFilter;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Activity>
 *
 * @method Activity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activity[]    findAll()
 * @method Activity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    //    /**
    //     * @return Handler[] Returns an array of Handler objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Handler
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findActivityByUser($user)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.host = :user')
            ->setParameter('user', $user)
            ->orderBy('a.startDatetime', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findPublishedActivity()
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.state = 2')
            ->orderBy('a.startDatetime', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findActivitiesBySearchFilter(SearchFilter $searchFilter, User $user)
    {
        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.startDatetime', 'DESC');

        if ($searchFilter->getCampus()) {
            $qb->andWhere('a.campus = :campus')
                ->setParameter('campus', $searchFilter->getCampus());
        }

        if ($searchFilter->getSearch()) {
            $qb->andWhere('a.name LIKE :search')
                ->setParameter('search', '%' . $searchFilter->getSearch() . '%');
        }

        if ($searchFilter->getStartDate()) {
            $qb->andWhere('a.startDatetime >= :startDate')
                ->setParameter('startDate', $searchFilter->getStartDate());
        }

        if ($searchFilter->getEndDate()) {
            $qb->andWhere('a.startDatetime <= :endDate')
                ->setParameter('endDate', $searchFilter->getEndDate());
        }

        if ($searchFilter->getOrganizer()) {
            $qb->andWhere('a.host = :user')
                ->setParameter('user', $user);
        }

        if ($searchFilter->getRegistered() && !$searchFilter->getNotRegistered()) {
            $qb->join('a.participants', 'p')
                ->andWhere('p = :user')
                ->setParameter('user', $user);
        }

        if ($searchFilter->getNotRegistered() && !$searchFilter->getRegistered()) {
            $qb->join('a.participants', 'p')
                ->andWhere('p != :user')
                ->setParameter('user', $user);
        }

        if($searchFilter->getFinished()){
            $qb->andWhere('a.state = 5');
        }else{
            $qb->andWhere('a.state != 1')
                ->andWhere('a.state != 5');
        }
        return $qb->getQuery()->getResult();
    }

    public function findPresentActivites(){
        return $this->createQueryBuilder('a')
            ->orWhere('a.state = 2')
            ->orWhere('a.state = 3')
            ->orWhere('a.state = 4')
            ->orderBy('a.startDatetime', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findCreatedNotPublishedActivities(User $user){
        return $this->createQueryBuilder('a')
            ->andWhere('a.host = :user')
            ->andWhere('a.state = 1')
            ->orderBy('a.startDatetime', 'DESC')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
