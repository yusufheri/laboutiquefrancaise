<?php

namespace App\Repository;

use App\Data\Search;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Undocumented function
     *
     * @param Search $search
     * @return Product[] Returnsan array of Product objects
     */
    public function findWithSearch(Search $search)
    {
        $query = $this->createQueryBuilder('p')
                        ->select('c', 'p')
                        ->join('p.category', 'c');

        if(!empty($search->categories)) {
            $query = $query
                            ->andWhere('c IN (:categories)')
                            ->setParameter('categories', $search->categories);
        }

        if (!(empty($search->search))) {
            $query = $query
                        ->andWhere('p.subTitle like :search')
                        ->setParameter('search', "%".$search->search."%");
        }

        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
