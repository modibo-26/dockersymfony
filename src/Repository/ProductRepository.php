<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
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

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Product[] Returns an array of Product objects
    */

    public function findProductsInSameCategory(Product $product): array {
        return $this->createQueryBuilder('p')
        ->andWhere('p.category = :category')
        ->andWhere('p.id != :idToExclude')
        ->setParameter('category', $product->getCategory())
        ->setParameter('idToExclude', $product->getId())
        ->orderBy('p.id', 'ASC')
        ->setMaxResults(4)
        ->getQuery()
        ->getResult()
        ;
    }

//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findProductsInSameCategoryDql(Product $product): array {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Product p
            WHERE p.category = :category
            AND p.id != :idToExclude
            ORDER BY p.id ASC
            LIMIT 4'
        )
        ->setParameter('category', $product->getCategory())
        ->setParameter('idToexclude', $product->getId())
        ->setMaxResults(4)
        ;

        return $query->getResult();
    }

    

    public function findProductsInSameCategorySql(Product $product): array {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM product p
        WHERE p.category_id = :categoryId
        AND p.id != :idToExclude
        ORDER BY p.id ASC
        LIMIT 1'
        ;

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([
            'categoryId' => $product->getCategory()->getId(),
            'idToExclude' => $product->getId()
        ]);

        return $resultSet->fetchAllAssociative();

    }

    public function findProductsByLastDate(): array {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Product p
            ORDER BY p.createdAt DESC'
        )
        ->setMaxResults(5)
        ;

        return $query->getResult();
    }

    public function findCategoryByLastDate(): array {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c
            FROM App\Entity\Category c
            ORDER BY c.id DESC'
        )
        ->setMaxResults(3)
        ;

        return $query->getResult();
    }
}
