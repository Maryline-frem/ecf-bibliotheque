<?php

namespace App\Repository;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Kind;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
    * @return Book[] Returns an array of Book objects
    */
    public function findByTitle($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.title LIKE :value')
            ->setParameter('value', "%{$value}%")
            ->orderBy('t.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByAuthor(int $id)
    {
        return $this->createQueryBuilder('i')
            ->innerJoin('i.author', 'a')
            ->andWhere('a.id = :author')
            ->setParameter('author', $id)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByKind(string $kind)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.kinds', 'k')
            ->andWhere('k.lastname LIKE :kind')
            ->setParameter('kind', "%{$kind}%")
            ->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
