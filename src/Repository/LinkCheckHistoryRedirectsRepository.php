<?php

namespace App\Repository;

use App\Entity\LinkCheck\LinkCheckHistoryRedirects;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LinkCheckHistoryRedirects>
 *
 * @method LinkCheckHistoryRedirects|null find($id, $lockMode = null, $lockVersion = null)
 * @method LinkCheckHistoryRedirects|null findOneBy(array $criteria, array $orderBy = null)
 * @method LinkCheckHistoryRedirects[]    findAll()
 * @method LinkCheckHistoryRedirects[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
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
