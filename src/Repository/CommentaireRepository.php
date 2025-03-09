<?php

namespace App\Repository;

use App\Entity\Commentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commentaire>
 */
class CommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);
    }



    public function findAverageNoteByActivity()
    {
        $qb = $this->createQueryBuilder('c')
                   ->select('IDENTITY(c.activity) as activity_id', 'AVG(c.note) AS avg_note')
                   ->groupBy('c.activity');
    
        $results = $qb->getQuery()->getResult();
    
        // Arrondir la moyenne 
        $averageNotes = [];
        foreach ($results as $result) {
            $averageNotes[$result['activity_id']] = round($result['avg_note']);
        }
    
        return $averageNotes;
    }
    
/**SELECT activity_id, ROUND(AVG(note)) AS avg_note FROM votes GROUP BY activity_id;




    //    

    //     * @return Commentaire[] Returns an array of Commentaire objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Commentaire
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
