<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentaireController extends AbstractController
{
    /**
     * @Route("/commentaire", name="commentaire")
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $commentaire = $this->getDoctrine()->getRepository(Commentaire::class)->findByExampleField();
        $repo2 = $this->getDoctrine()->getRepository(Commentaire::class)->findByExampleField3();

        $count = count($commentaire);
        $repo = $paginator->paginate(
            $commentaire,
            $request->query->getInt('page',1),
            10
        );



        return $this->render('commentaire/index.html.twig', [
            'repo' => $repo,
            'count'=> $count,
            'repo2'=>$repo2]);

    }

    /**
     * @Route("/ajout/commentaire", name="add_commentaire")
     */
    public function commentaire(Request $request)
    {

        $post = new Commentaire();

        $post->setUser($this->getUser());

        $form = $this->createForm(CommentaireType::class, $post);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($post);
            $doctrine->flush();
            $this->addFlash('success', 'Nous vous remercions pour votre commentaire.');
            return $this->redirectToRoute('commentaire');
            }


        $repo = $this->getDoctrine()->getRepository(Commentaire::class)->findByExampleField4();

        return $this->render('commentaire/commentaire.html.twig', array(
            'form' => $form->createView(),
            'repo' => $repo
        ));
    }
}