<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentaireController extends AbstractController
{
    public $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        return $this->entityManager = $entityManager;

    }

    /**
     * @Route("/ajouter/avis", name="add_avis")
     */
    public function commentaire(Request $request)
    {
        $user = $this->getUser();

        $commentaire = new Commentaire();

        $form = $this->createForm(CommentaireType::class, $commentaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setUser($user);
            $commentaire->setBool(0);
            $this->entityManager->persist($commentaire);
            $this->entityManager->flush();
            $this->addFlash('success', 'Nous vous remercions pour votre commentaire.');
           // return $this->redirectToRoute('commentaire');
        }

        return $this->render('commentaire/commentaire.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/avis", name="avis")
     */
    public function commentaireVoir (PaginatorInterface $paginator, Request $request, CommentRepository $commentRepository)
    {
        $commentaire = $commentRepository->findby(['bool' => 1]);

        $count = count($commentaire);


        $moyenne = $commentRepository->moyenne();


        $repo = $paginator->paginate(
            $commentaire,
            $request->query->getInt('page',1),
            10
        );

        return $this->render('commentaire/index.html.twig', [
            'repo' => $repo,
            'count'=> $count,
            'moyenne' => $moyenne
            ]);
    }
}