<?php

namespace App\Controller;

use App\Form\CoffretType;
use App\Form\PersonneType;
use App\Repository\CoachingTarifRepository;
use App\Repository\CoffretRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CoffretsCadeauxController extends AbstractController
{
    /**
     * @Route("/coffretscadeaux", name="coffret")
     */
    public function index(CoffretRepository $coffretRepository): Response
    {
        $coffretTarif = $coffretRepository->findAll();

        return $this->render('coffrets_cadeaux/index.html.twig', [
            'coffret' => $coffretTarif,
        ]);
    }

    /**
     * @Route("/coffret/ajouter/{titre}", name="coffret_add")
     */
    public function commentaireAdd ($titre, Request $request, CoffretRepository $coffret)
    {
        $coffretAdd = $coffret->findOneBy(['produit' => $titre]);

        $id = $coffretAdd->getId();

        $form = $this->createForm(CoffretType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $heure = $form['heure']->getData();
            $information = $form['information']->getData();

            $session = new Session();
            $panier =$session->get('coffret',[]);

            $panier[$id] = [
                'quantity' => 1,
                'heure' => $heure,
                'information' => $information
            ];

            if( $panier[$id]['heure'] < 1){
                unset ($panier[$id]);
            }

            $session->set('coffret', $panier);

            return $this->redirectToRoute('panier');
        }
        return $this->render('coffrets_cadeaux/coffret.html.twig', [
            'form' =>$form->createView(),
            'coffret' => $coffretAdd
        ]);
    }
}
