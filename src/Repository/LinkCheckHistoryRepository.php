<?php

namespace App\Repository;

use App\Entity\LinkCheck\LinkCheckHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LinkCheckHistory>
 *
 * @method LinkCheckHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method LinkCheckHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method LinkCheckHistory[]    findAll()
 * @method LinkCheckHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkCheckHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LinkCheckHistory::class);
    }

    public function save(LinkCheckHistory $entity, bool $flush = false): void
    {
        $entity->setDateAdd();
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LinkCheckHistory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getLinkCheckData(): array
    {
        return $this->createQueryBuilder('lch')
            ->leftJoin('lch.redirects', 'lchr')
            ->getQuery()
            ->getResult();
    }
}
