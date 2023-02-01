<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Dish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dish>
 *
 * @method Dish|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dish|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dish[]    findAll()
 * @method Dish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DishRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Dish::class);
  }

  public function save(Dish $entity, bool $flush = false): void
  {
    $this->getEntityManager()->persist($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  public function remove(Dish $entity, bool $flush = false): void
  {
    $this->getEntityManager()->remove($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  /**
   * @return integer Returns the number of dishes for each category.
   */
  public function getCountDishesByCategories(): array
  {
    return $this->createQueryBuilder('d')
        ->select('d.category_id, count(d.category_id) as n')
        ->from('dish', 'd')
        ->groupBy('d.category_id')
        ->getQuery()
        ->getResult();
  }

  public function getCountDishesByCategories2()
  {
    $entityManager = $this->getEntityManager();

      return $entityManager->createQueryBuilder()
          ->select('c.id, c.Name, count(d.Name) as nbDishes')
          ->from(Category::class, 'c')
          ->innerJoin('c.dishes', 'd')
          ->groupBy('c.Name')
          ->getQuery()
          ->getResult()
          ;
  }

  public function findStickies($category, $limit)
  {
    return $this->createQueryBuilder('d')
      ->where('d.category = :category')
      ->andWhere('d.Sticky = 1')
      ->setParameter('category', $category)
      ->setMaxResults($limit)
      ->getQuery()
      ->getResult();
  }

//    /**
//     * @return Dish[] Returns an array of Dish objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Dish
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
