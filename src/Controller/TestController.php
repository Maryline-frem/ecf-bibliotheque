<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\BorrowerRepository;
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
        UserRepository $userRepository): Response
    {
        // Récupération de l'entity manager.
        $entityManager = $this->getDoctrine()->getManager();

        // Récupération de tous les utilisateurs.
        $users = $userRepository->findAll();
        dump($users);

        // Récupération de l'utilisateur dont l'id est 1.
        $user = $userRepository->find(1);
        dump($user);

        // Récupération de l'utilisateur pas l'email.
        $mail = 'foo.foo@example.com';

        $user = $userRepository->findByEmail($mail);
        dump($user);
        
        // Récupération des utilisateurs dont l'attribut `roles` contient le mot clé `ROLE_EMRUNTEUR`.
        // Requête a revoir !!!!!!!
        // $users = $userRepository->findByRole('ROLE_EMRUNTEUR');
        // dump($users);

        // Récupération de la liste complète de tous les livres.
        $books = $bookRepository->findAll();
        dump($books);
        exit();
    }
}
