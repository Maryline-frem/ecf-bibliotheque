<?php

namespace App\Repository;

use App\Entity\Borrowing;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Borrowing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Borrowing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Borrowing[]    findAll()
 * @method Borrowing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BorrowingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Borrowing::class);
    }

    /**
     * @return Borrowing[] Returns an array of Borrowing objects
     */

    public function findByBorrowing()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.borrowing_date', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByBorrower($value)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.borrower', 'k')
            ->andWhere('k.id = :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByBook($value)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.book', 'k')
            ->andWhere('k.id = :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByReturnDate($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.return_date < :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByReturn($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.return_date IS NULL')
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByBookNotReturn(int $value)
    {
        return $this->createQueryBuilder('b')
            ->where('b.id = :val')
            ->andWhere('b.return_date IS NULL')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByUser(User $user, string $role = '')
    {
        // 'p' sera l'alias qui permet de désigner un profil
        return $this->createQueryBuilder('p')
            // Demande de jointure de l'objet user.
            // 'u' sera l'alias qui permet de désigner un user.
            ->innerJoin('p.user', 'u')
            // Ajout d'un filtre qui ne retient que le profil
            // qui possède une relation avec la variable :user.
            ->andWhere('p.user = :user')
            // Ajout d'un filtre qui ne retient que les users
            // qui contiennent (opérateur LIKE) la chaîne de
            // caractères contenue dans la variable :role.
            ->andWhere('u.roles LIKE :role')
            // Affectation d'une valeur à la variable :user.
            ->setParameter('user', $user)
            // Affectation d'une valeur à la variable :role.
            // Le symbole % est joker qui veut dire
            // « match toutes les chaînes de caractères ».
            ->setParameter('role', "%{$role}%")
            // Récupération d'une requête qui n'attend qu'à être exécutée.
            ->getQuery()
            // Exécution de la requête.
            // Récupération d'une variable qui peut contenir
            // un profil ou la valeur nulle.
            ->getOneOrNullResult()
        ;
    }

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
    public function findOneBySomeField($value): ?Borrowing
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
