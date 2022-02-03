<?php

namespace App\Controller;


use App\Form\PersonneType;
use App\Repository\CoachingTarifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoachingController extends AbstractController
{
    /**
     * @Route("/coaching", name="coaching")
     */
    public function index(CoachingTarifRepository $coachingTarifRepository ): Response
    {
        $coachingTarif = $coachingTarifRepository->findAll();

        return $this->render('coaching/index.html.twig', [
            'coaching' => $coachingTarif,
        ]);
    }


    /**
     * @Route("/coaching/ajouter/{slug}", name="coaching_add")
     */
    public function commentaireAdd ($slug, Request $request, CoachingTarifRepository $coaching)
    {
        $coachingAdd = $coaching->findOneBy(['slug' => $slug]);

        $id = $coachingAdd->getId();

        $form = $this->createForm(PersonneType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $personne = $form['personne']->getData();

            $session = $request->getSession();
            $panier =$session->get('coaching',[]);

            $panier[$id] = [
                'quantity' => 1,
                'personne' => $personne
            ];

            $session->set('coaching', $panier);

            return $this->redirectToRoute('panier');
        }
        return $this->render('coaching/coaching.html.twig', [
            'form' =>$form->createView(),
            'coaching' => $coachingAdd
        ]);
    }
}
