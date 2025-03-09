<?php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Activity>
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function findApprovedActivities(): array
{
    return $this->createQueryBuilder('a')
        ->where('a.isApproved = :approved')
        ->setParameter('approved', true)
        ->getQuery()
        ->getResult();
}



// src/Repository/ActivityRepository.php

public function getActivityCountByTypeAndDateLast10Days(): array
{
    $tenDaysAgo = new \DateTime();
    $tenDaysAgo->modify('-10 days');

    return $this->createQueryBuilder('a')
        ->select("SUBSTRING(a.createdAt, 1, 10) as date, a.typesActivity as type, COUNT(a.id) as count")
        ->where('a.createdAt >= :tenDaysAgo')
        ->setParameter('tenDaysAgo', $tenDaysAgo)
        ->groupBy("date, type")
        ->orderBy("date", "ASC")
        ->getQuery()
        ->getResult();
}





//SELECT SUBSTRING(created_at, 1, 10) AS date,
    //   types_activity AS type,
  //     COUNT(id) AS count
//FROM activity
//WHERE created_at >= NOW() - INTERVAL 10 DAY
//GROUP BY date, type
//ORDER BY date ASC
//LIMIT 25;




//    public function findOneBySomeField($value): ?Activity
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery(
//            ->getOneOrNullResult()
//        ;
//    }
}
