<?php

namespace App\Controller;

use App\Repository\CoachingTarifRepository;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(SessionInterface $session, EvenementRepository $evenementRepository, CoachingTarifRepository $coachingTarifRepository): Response
    {
        $evenement = $session->get('evenement', []);
        $coaching = $session->get('coaching', []);

        $evenementWithData = [];

        foreach ($evenement as $id => $quantity) {
            $evenementWithData[] = [
                'product' => $evenementRepository->find($id),
                'quantity' => $quantity
            ];
        }

$totalEvenement = 0;
        foreach ($evenementWithData as $item) {
            $totalItem = $item['product']->getPrix() * $item['quantity'];
            $totalEvenement += $totalItem;
        }

        $coachingWithData = [];

        foreach ($coaching as $id => $quantity) {
            $coachingWithData[] = [
                'product' => $coachingTarifRepository->find($id),
                'quantity' => $quantity
            ];
        }

        $totalCoaching = 0;
        foreach ($coachingWithData as $item) {
            if ($item['quantity']['personne']  >2){
                $totalItem = $item['product']->getPriceForTwo() + $item['product']->getPriceForThree() * ($item['quantity']['personne'] - 2);
            } else {
                $totalItem = $item['product']->getPriceForTwo();
            }
            $totalCoaching += $totalItem;
        }

        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }
}
