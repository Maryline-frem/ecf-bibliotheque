<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Borrower;
use App\Entity\Borrowing;
use App\Entity\Kind;
use App\Entity\User;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\BorrowerRepository;
use App\Repository\BorrowingRepository;
use App\Repository\KindRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/test")
 */
class TestController extends AbstractController
{
    /**
     * @Route("/", name="test")
     */
    public function index(
        AuthorRepository $authorRepository,
        BookRepository $bookRepository,
        BorrowerRepository $borrowerRepository,
        BorrowingRepository $borrowingRepository,
        KindRepository $kindRepository,
        UserRepository $userRepository): Response
    {
        // Les utilisateurs
        // Récupération de l'entity manager.
        $entityManager = $this->getDoctrine()->getManager();

        // Récupération de tous les utilisateurs.
        $users = $userRepository->findAll();
        // dump($users);

        // Récupération de l'utilisateur dont l'id est 1.
        $user = $userRepository->find(1);
        // dump($user);

        // Récupération de l'utilisateur pas l'email.
        $mail = 'foo.foo@example.com';
        $user = $userRepository->findByEmail($mail);
        // dump($user);
        
        // Récupération des utilisateurs dont l'attribut `roles` contient le mot clé `ROLE_EMRUNTEUR`.
        $users = $userRepository->findByRole('ROLE_EMPRUNTEUR');
        // dump($users);


        // Les livres
        // Requêtes de lecture :
        // Récupération de la liste complète de tous les livres.
        $books = $bookRepository->findAll();
        // dump($books);

        // Récupération des données du livre dont l'id est 1.
        $book = $bookRepository->find(1);
        // dump($book);

        // Récupération de la liste des livres dont le titre contient le mot clé `lorem`.
        $title = 'Lorem';
        $book = $bookRepository->findByTitle($title);
        // dump($book);

        // Récupération de la liste des livres dont l'id de l'auteur est `2`.
        $book = $bookRepository->findByAuthor(2);
        // dump($book);

        // Récupération de la liste des livres dont le genre contient le mot clé `roman`.
        $kind = 'Roman';     
        $book = $bookRepository->findByKind($kind);
        // dump($book);

        // Requêtes de création :
        // Ajouter un nouveau livre.
        // Récupération de la liste complète des authors.
        $authors = $authorRepository->findAll();
        // Affectation de l'author à la variable $author2.
        $author2 = $authors[1];

        // Récupération de la liste complète des kinds.
        $kinds = $kindRepository->findAll();
        // Affectation de l'kind à la variable $kind6.
        $kind6 = $kinds[5];

        $book = new Book();
        $book->setTitle('Totum autem id externum');
        $book->setEditionYear('2020');
        $book->setPageNumber('300');
        $book->setCodeIsbn('9790412882714');
        $book->setAuthor($author2);
        $book->addKind($kind6);

        $entityManager->persist($book);
        $entityManager->flush();

        // Requêtes de mise à jour.
        // Récupération de la liste complète des kinds.
        $kinds = $kindRepository->findAll();
        // Affectation de kind à la variable $kind5.
        $kind2 = $kinds[1];
        // Affectation de kind à la variable $kind5.
        $kind5 = $kinds[4];
        // Récupération du deuxième book.
        $book = $bookRepository->findAll()[1];
        // Changement du titre du book.
        $book->setTitle('Aperiendum est igitur');
        // Suppression d'une relation avec un kind.
        $book->removeKind($kind2);
        // Changement du kind du book.
        $book->addKind($kind5);
        // Exécution des requêtes.
        // C-à-d envoi de la requête SQL à la BDD.
        $entityManager->flush();
        // dump($book);

        // // Supprimer le livre dont l'id est `123`.
        // // Récupération de la liste complète des books.
        // $books = $bookRepository->findAll();
        // // Affectation de book à la variable $book123.
        // $book123 = $books[122];
        // // Suppression d'un book.
        // $entityManager->remove($book123);
        // $entityManager->flush();
        // dump($book123);

        // // Supprimer le livre dont l'id est `123`.
        // $id = [123];
        // $book = $bookRepository->find($id);
        // // Suppression d'un book.
        // $entityManager->remove($book);
        // $entityManager->flush();
        // // dump($book123);


        // Les emprunteurs
        // La liste complète des emprunteurs
        $borrowers = $borrowerRepository->findAll();
        // dump($borrowers);

        // Les données de l'emprunteur dont l'id est `3`.
        $borrower = $borrowerRepository->find(3);
        // dump($borrower);

        // Les données de l'emprunteur qui est relié au user dont l'id est `3`.
        $borrower = $borrowerRepository->findByUser(3);
        // dump($borrower);

        // La liste des emprunteurs dont le nom ou le prénom contient le mot clé `foo`.
        $name = 'foo';
        $borrowers = $borrowerRepository->findByFirstnameOrLastname($name);
        // dump($borrowers);

        // La liste des emprunteurs dont le téléphone contient le mot clé `1234`.
        $phone = '1234';
        $borrower = $borrowerRepository->findByPhone($phone);
        // dump($borrower);

        // La liste des emprunteurs dont la date de création est antérieure au 01/03/2021 exclu.
        $date = '2021-03-01';
        $borrowers = $borrowerRepository->findByCreationDate($date);
        // dump($borrowers);

        // La liste des emprunteurs inactifs (c-à-d dont l'attribut `actif` est égal à `false`).
        $isActive = false;
        $borrowers = $borrowerRepository->findByActive($isActive);
        // dump($borrowers);


        // Les emprunts
        // La liste des 10 derniers emprunts au niveau chronologique.
        $borrowings = $borrowingRepository->findByBorrowing();
        // dump($borrowings);

        // La liste des emprunts de l'emprunteur dont l'id est `2`.
        $id = [2];
        $borrower = $borrowingRepository->findByBorrower($id);
        // dump($borrower);

        // La liste des emprunts du livre dont l'id est `3`.
        $id = [3];
        $book = $borrowingRepository->findByBook(3);
        // dump($book);

        // La liste des emprunts qui ont été retournés avant le 01/01/2021.
        $date = '2021-01-01';
        $borrowings = $borrowingRepository->findByReturnDate($date);
        // dump($borrowings);

        // La liste des emprunts qui n'ont pas encore été retournés (c-à-d dont la date de retour est nulle).
        $notReturn = NULL;
        $borrowings = $borrowingRepository->findByReturn($notReturn);
        // dump($borrowings);

        // Les données de l'emprunt du livre dont l'id est `3` et qui n'a pas encore été retournés (c-à-d dont la date de retour est nulle).
        $id = [3];
        $borrowing = $borrowingRepository->findByBookNotReturn($id);
        // dump($borrowing);

        // Requêtes de création.
        // Récupération de la liste complète des borrowers.
        $borrowers = $borrowerRepository->findAll();
        // Affectation d'un borrower à la variable $borrower.
        $borrower = $borrowers[0];
        // Récupération de la liste complète des books.
        $books = $bookRepository->findAll();
        // Affectation d'un book à la variable $book.
        $book = $books[0];

        // Création d'un nouvel emprunt.
        $borrowing = new Borrowing();
        $borrowing->setBorrowingDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-12-01 16:00:00'));
        $borrowing->setBorrower($borrower);
        $borrowing->setBook($book);
        // $entityManager->persist($borrowing);
        // $entityManager->flush();
        // dump($borrowing);

        // // Requêtes de mise à jour.
        // // Modifier l'emprunt dont l'id est `3`.
        // $borrowings = $borrowingRepository->findAll();
        // // Affectation d'un borrowing à la variable $borrower.
        // $borrowing = $borrowings[2];
        // $borrowing->setReturnDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-05-01 10:00:00'));
        // $entityManager->persist($borrowing);
        // $entityManager->flush();
        // dump($borrowing);

        // // Requêtes de suppression.
        // // Supprimer l'emprunt dont l'id est `42`.
        // $id = [41];
        // $borrowing = $borrowingRepository->find($id);
        // $entityManager->remove($borrowing);
        // $entityManager->flush();

        exit();
    }
}