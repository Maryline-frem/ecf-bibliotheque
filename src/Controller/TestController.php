<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Kind;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\BorrowerRepository;
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
        BookRepository $bookRepository,
        KindRepository $kindRepository,
        UserRepository $userRepository): Response
    {
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
        dump($book);

        // Récupération de la liste des livres dont le genre contient le mot clé `roman`.
        // A revoir
        // $kind = 'Roman';
        // $book = $bookRepository->findByTitle($kind);
        // dump($book);

        // Ajouter un nouveau livre.

        // Requêtes de mise à jour.

        // Supprimer le livre dont l'id est `123`.
        exit();
    }
}
