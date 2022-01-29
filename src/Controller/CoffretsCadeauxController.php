<?php

namespace App\Controller;

use App\Form\PersonneType;
use App\Repository\CoachingTarifRepository;
use App\Repository\CoffretRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoffretsCadeauxController extends AbstractController
{
    /**
     * @Route("/coffretcadeaux", name="coffret")
     */
    public function index(CoffretRepository $coffretRepository): Response
    {
        $coffretTarif = $coffretRepository->findAll();

        return $this->render('coffrets_cadeaux/index.html.twig', [
            'coffret' => $coffretTarif,
        ]);
    }

    /**
     * @Route("/coffret/add/{id}", name="coffret_add")
     */
    public function commentaireAdd ($id, Request $request)
    {
        $session = $request->getSession();

        $panier = $session->get('coffret', []);

        if(!empty($panier[$id])){
            $panier[$id] ++ ;
        } else {
            $panier[$id] = 1;
        }
        $session->set('coffret', $panier);

        return $this->redirectToRoute('panier');
    }
}
