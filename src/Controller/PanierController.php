<?php

namespace App\Controller;

use App\Entity\ResaCoaching;
use App\Repository\CoachingTarifRepository;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(EntityManagerInterface $entityManager, MailerInterface $mailer,SessionInterface $session, EvenementRepository $evenementRepository, CoachingTarifRepository $coachingTarifRepository): Response
    {
        if(!$this->getUser()){
            $this->addFlash('info', "Vous devez être connecté pour accéder au récapitulatif de votre réservation");
            return  $this->redirectToRoute('app_login');
        } else {

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


            foreach ( $coachingWithData as $data){
               $coachingId = $data['product']->getId();
                $coachId = $coachingTarifRepository->find($coachingId);
            }




            $totalCoaching = 0;
            foreach ($coachingWithData as $item) {
                if ($item['quantity']['personne'] > 2) {
                    $totalItem = $item['product']->getPriceForTwo() + $item['product']->getPriceForThree() * ($item['quantity']['personne'] - 2);
                } else {
                    $totalItem = $item['product']->getPriceForTwo();
                }
                $totalCoaching += $totalItem;
            }

            if($coachingWithData) {
                foreach ($coachingWithData as $data) {
                    $coachingData = new ResaCoaching();
                    $coachingData->setUser($this->getUser());
                    $coachingData->setCoaching($coachId);
                    $coachingData->setNbPersonne($data['quantity']['personne']);
                    $coachingData->setResaConfirm(0);
                    $coachingData->setPrix($totalCoaching);
                    $entityManager->persist($coachingData);
                    $entityManager->flush();
                }
            }
        }

        return $this->render('panier/index.html.twig', [
            'evenement' => $evenementWithData,
            'coaching' => $coachingWithData,
            'totalEvenement' => $totalEvenement,
            'totalCoaching' => $totalCoaching
        ]);
    }
}
