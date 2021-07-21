<?php

namespace App\Repository;

use App\Entity\Borrower;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Borrower|null find($id, $lockMode = null, $lockVersion = null)
 * @method Borrower|null findOneBy(array $criteria, array $orderBy = null)
 * @method Borrower[]    findAll()
 * @method Borrower[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BorrowerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Borrower::class);
    }

    /**
     * @return Borrower[] Returns an array of Borrower objects
     */

    public function findByUser(int $id)
    {
        return $this->createQueryBuilder('i')
            ->innerJoin('i.user', 'u')
            ->andWhere('u.id = :user')
            ->setParameter('user', $id)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByFirstnameOrLastname($value)
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->where($qb->expr()->orX(
                $qb->expr()->like('s.firstname', ':value'),
                $qb->expr()->like('s.lastname', ':value')
            ))
            ->setParameter('value', "%{$value}%")
            ->orderBy('s.firstname', 'ASC')
            ->orderBy('s.lastname', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByPhone($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.phone LIKE :value')
            ->setParameter('value', "%{$value}%")
            ->orderBy('p.phone', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByCreationDate($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.creationDate < :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByActive($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.active = :value')
            ->setParameter('value', $value)
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
    public function findOneBySomeField($value): ?Borrower
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
