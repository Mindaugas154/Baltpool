<?php

namespace App\Repository;

use App\Entity\LinkCheck\LinkCheckHistoryRedirects;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LinkCheckHistoryRedirects>
 */
class LinkCheckHistoryRedirectsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LinkCheckHistoryRedirects::class);
    }

    public function save(LinkCheckHistoryRedirects $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LinkCheckHistoryRedirects $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getLinkCheckDataSql(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        //pasiimti viską per 1 sql
        $sql = 'SELECT lch.*, lchr.*
            FROM link_check_history lch
            LEFT JOIN link_check_history_redirects lchr
                ON (lchr.id_link_history_id = lch.id)
            ORDER BY lch.id ASC, lchr.id_redirects ASC';

        $resultSet = $conn->executeQuery($sql);

        return $resultSet->fetchAllAssociative();
    }

//    /**
//     * @return LinkCheckHistoryRedirects[] Returns an array of LinkCheckHistoryRedirects objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LinkCheckHistoryRedirects
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
