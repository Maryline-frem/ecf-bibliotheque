<?php

namespace App\Controller;

use App\Entity\Borrower;
use App\Form\BorrowerType;
use App\Repository\BorrowerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/borrower")
 */
class BorrowerController extends AbstractController
{
    /**
     * @Route("/", name="borrower_index", methods={"GET"})
     */
    public function index(BorrowerRepository $borrowerRepository): Response
    {
        return $this->render('borrower/index.html.twig', [
            'borrowers' => $borrowerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="borrower_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $borrower = new Borrower();
        $form = $this->createForm(BorrowerType::class, $borrower);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($borrower);
            $entityManager->flush();

            return $this->redirectToRoute('borrower_index');
        }

        return $this->render('borrower/new.html.twig', [
            'borrower' => $borrower,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="borrower_show", methods={"GET"})
     */
    public function show(Borrower $borrower): Response
    {
        // Un borrower n'a pas le droit de consulter le profil d'un autre borrower.  
        // On vérifie si l'utilisateur est un borrower et si c'est le cas,
        // on vérifie s'il demande le même profile que son profile borrower.
        // S'il demande le profile d'un autre utilisateur on le redirige vers
        // son propre profile.
        $response = $this->redirectBorrower('borrower_show', $borrower, $borrowerRepository);

        if ($response) {
            return $response;
        }
        return $this->render('borrower/show.html.twig', [
            'borrower' => $borrower,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="borrower_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Borrower $borrower): Response
    {
        $form = $this->createForm(BorrowerType::class, $borrower);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('borrower_index');
        }

        return $this->render('borrower/edit.html.twig', [
            'borrower' => $borrower,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="borrower_delete", methods={"POST"})
     */
    public function delete(Request $request, Borrower $borrower): Response
    {
        if ($this->isCsrfTokenValid('delete'.$borrower->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($borrower);
            $entityManager->flush();
        }

        return $this->redirectToRoute('borrower_index');
    }
}
