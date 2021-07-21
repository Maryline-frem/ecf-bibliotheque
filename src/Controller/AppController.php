<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\BookRepository;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app")
     */
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('app/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }
}
