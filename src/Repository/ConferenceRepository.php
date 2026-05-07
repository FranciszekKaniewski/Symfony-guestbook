<?php

namespace App\Repository;

use App\Entity\Conference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conference>
 */
class ConferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conference::class);
    }

    public function findWithMostComments(int $limit = 5): array
    {
        return $this->createQueryBuilder('c')
            ->select('c', 'COUNT(m.id) AS HIDDEN comment_count') // Liczymy komentarze
            ->leftJoin('c.comments', 'm') // Dołączamy tabelę komentarzy
            ->groupBy('c.id')
            ->orderBy('comment_count', 'DESC') // Sortujemy po wyliczonej liczbie
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
