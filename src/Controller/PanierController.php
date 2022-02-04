<?php

namespace App\Controller;

use App\Entity\ResaCoaching;
use App\Form\CommentResaType;
use App\Form\contactType;
use App\Form\CoordonneesType;
use App\Repository\CoachingTarifRepository;
use App\Repository\CoffretRepository;
use App\Repository\EvenementRepository;
use App\Repository\ResaCoachingRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(CoffretRepository $coffretRepository, EntityManagerInterface $entityManager, SessionInterface $session, EvenementRepository $evenementRepository, CoachingTarifRepository $coachingTarifRepository): Response
    {
       if (!$this->getUser()){
            $this->addFlash('info', "Vous devez être connecté pour accéder au récapitulatif de votre réservation");
            return  $this->redirectToRoute('app_login');
        } else {
            //Récupère les paniers
           $coffret = $session->get('coffret', []);
            $evenement = $session->get('evenement', []);
            $coaching = $session->get('coaching', []);


            //coffret

           $coffretWithData = [];

           //Transforme le panier en array
           foreach ($coffret as $id => $quantity) {
               $coffretWithData[] = [
                   'product' => $coffretRepository->find($id),
                   'quantity' => $quantity
               ];
           }

               //Calcul total
           $totalCoffret = 0;
           foreach ($coffretWithData as $item3) {
                   $totalItem = $item3['product']->getPrix() * $item3['quantity']['heure'];
                    $totalCoffret += $totalItem;
               }

            //Evenement
            $evenementWithData = [];

            //Transforme Evenement en array
            foreach ($evenement as $id => $quantity) {
                $evenementWithData[] = [
                    'product' => $evenementRepository->find($id),
                    'quantity' => $quantity
                ];
            }

           //Calcul total evenement
           $totalEvenement = 0;
           foreach ($evenementWithData as $item) {
               $totalItem2 = $item['product']->getPrix() * $item['quantity'];
               $totalEvenement += $totalItem2;
           }

           //Coaching
           $coachingWithData = [];

            //Transforme le panier en array
            foreach ($coaching as $id => $quantity) {
                $coachingWithData[] = [
                    'product' => $coachingTarifRepository->find($id),
                    'quantity' => $quantity
                ];
            }

          //Calcul total
            $totalCoaching = 0;
            foreach ($coachingWithData as $item) {
                if ($item['quantity']['personne'] > 2) {
                    $totalItem = $item['product']->getPriceForTwo() + $item['product']->getPriceForThree() * ($item['quantity']['personne'] - 2);
                } else {
                    $totalItem = $item['product']->getPriceForTwo();
                }
                $totalCoaching += $totalItem;
            }
        }

        return $this->render('panier/index.html.twig', [
            'evenement' => $evenementWithData,
            'coaching' => $coachingWithData,
            'totalEvenement' => $totalEvenement,
            'totalCoaching' => $totalCoaching,
            'coffret'=>$coffretWithData,
            'totalCoffret' =>$totalCoffret
        ]);
    }


    /**
     * @Route("/confirmationdevoscoordonnees", name="confirmationCoordonee")
     */
    public function confirmationCoordonnee(Request $request, EntityManagerInterface $entityManager, CoffretRepository $coffretRepository, SessionInterface $session, EvenementRepository $evenementRepository, CoachingTarifRepository $coachingTarifRepository): Response
    {

        $user = $this->getUser();

        $form = $this->createForm(CoordonneesType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();


            //Récupère les paniers
            $coffret = $session->get('coffret', []);
            $evenement = $session->get('evenement', []);
            $coaching = $session->get('coaching', []);


            //coffret

            $coffretWithData = [];

            //Transforme le panier en array
            foreach ($coffret as $id => $quantity) {
                $coffretWithData[] = [
                    'product' => $coffretRepository->find($id),
                    'quantity' => $quantity
                ];
            }

            //Calcul total
            $totalCoffret = 0;
            foreach ($coffretWithData as $item3) {
                $totalItem = $item3['product']->getPrix() * $item3['quantity']['heure'];
                $totalCoffret += $totalItem;
            }

            //Evenement
            $evenementWithData = [];

            //Transforme Evenement en array
            foreach ($evenement as $id => $quantity) {
                $evenementWithData[] = [
                    'product' => $evenementRepository->find($id),
                    'quantity' => $quantity
                ];
            }

            //Calcul total evenement
            $totalEvenement = 0;
            foreach ($evenementWithData as $item) {
                $totalItem2 = $item['product']->getPrix() * $item['quantity'];
                $totalEvenement += $totalItem2;
            }

            //Coaching
            $coachingWithData = [];

            //Transforme le panier en array
            foreach ($coaching as $id => $quantity) {
                $coachingWithData[] = [
                    'product' => $coachingTarifRepository->find($id),
                    'quantity' => $quantity
                ];
            }

            //Calcul total
            $totalCoaching = 0;
            foreach ($coachingWithData as $item) {
                if ($item['quantity']['personne'] > 2) {
                    $totalItem = $item['product']->getPriceForTwo() + $item['product']->getPriceForThree() * ($item['quantity']['personne'] - 2);
                } else {
                    $totalItem = $item['product']->getPriceForTwo();
                }
                $totalCoaching += $totalItem;
            }

            //Ref commande
            $debut = new DateTime('now');
            $uniq = $debut->format('dmY') . '-' . uniqid();

            //Inscription BDD réservation
            if ($coachingWithData or $evenementWithData or $coffretWithData) {
                $now = new DateTime('now');
                if ($evenementWithData) {
                    foreach ($evenementWithData as $evenement) {
                        $evenementId = $evenement['product']->getId();
                        $coachId = $evenementRepository->find($evenementId);
                        $coachingData = new ResaCoaching();
                        $coachingData->setUser($this->getUser());
                        $coachingData->setDateResa($now);
                        $coachingData->setNumeroDeCommande($uniq);
                        $coachingData->setEvenement($coachId);
                        $coachingData->setResaConfirm(0);
                        $coachingData->setPrix($totalEvenement);
                        $entityManager->persist($coachingData);
                        $entityManager->flush();
                    }
                }

                if ($coffretWithData) {
                    foreach ($coffretWithData as $coffret) {
                        $coffretId = $coffret['product']->getId();
                        $coachId = $coffretRepository->find($coffretId);
                        $coachingData = new ResaCoaching();
                        $coachingData->setUser($this->getUser());
                        $coachingData->setDateResa($now);
                        $coachingData->setNumeroDeCommande($uniq);
                        $coachingData->setCoffretProduit($coachId);
                        $coachingData->setResaConfirm(0);
                        $coachingData->setPrix($totalCoffret / 100);
                        $coachingData->setHeuresCoffret($coffret['quantity']['heure']);
                        $entityManager->persist($coachingData);
                        $entityManager->flush();
                    }
                }

                if ($coachingWithData) {
                    foreach ($coachingWithData as $data) {
                        $coachingId = $data['product']->getId();
                        $coachId = $coachingTarifRepository->find($coachingId);
                        $coachingData = new ResaCoaching();
                        $coachingData->setUser($this->getUser());
                        $coachingData->setDateResa($now);
                        $coachingData->setNumeroDeCommande($uniq);
                        $coachingData->setCoaching($coachId);
                        $coachingData->setNbPersonne($data['quantity']['personne']);
                        $coachingData->setResaConfirm(0);
                        if ($data['quantity']['personne'] > 2) {
                            $totalItem = $data['product']->getPriceForTwo() + $data['product']->getPriceForThree() * ($data['quantity']['personne'] - 2);
                        } else {
                            $totalItem = $data['product']->getPriceForTwo();
                        }
                        $coachingData->setPrix($totalItem);
                        $entityManager->persist($coachingData);
                        $entityManager->flush();
                    }
                }
            }

            return $this->redirectToRoute('attenteConfirmation');
        }

        return $this->render('panier/confirmationCoordonnee.html.twig', [
                'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/attenteconfirmationcommande", name="attenteConfirmation")
     */
    public function detail(ResaCoachingRepository $resaCoachingRepository): Response
    {
        $user = $this->getUser();

        $lastResa = $resaCoachingRepository->derniereCommande($user);

        $commande = $lastResa[0]['numeroDeCommande'];

        $resa = $resaCoachingRepository->findOneBy(['numeroDeCommande'=> $commande] );

        return $this->render('panier/confirmation.html.twig', [
            'resa' => $resa
        ]);
    }

    /**
     * @Route("/validationreservation/{numeroDeCommande}", name="validationConfirmation")
     */
    public function validation($numeroDeCommande, ResaCoachingRepository $resaCoachingRepository, EntityManagerInterface $entityManager, Request $request, MailerInterface $mailer): Response
    {
        $this->get('session')->remove('coffret');
        $this->get('session')->remove('evenement');
        $this->get('session')->remove('coaching');

        $coach = $resaCoachingRepository->findBy(['numeroDeCommande' => $numeroDeCommande]);

        $commandeEmail = $coach[0]->getNumeroDeCommande();
/*
        $email = (new Email())
            ->from('jodelap.coaching@gmail.com')
            ->to('jodelap.coaching@gmail.com')
            ->subject('Confirmation de réservation'.' '.$commandeEmail)
            ->text('Numéro de réservation :'.' '.$commandeEmail.' '.'Good Luck Chuck');

        $mailer->send($email);
*/
        foreach($coach as $coach2){
            $coach2->setResaConfirm(1);
            $entityManager->persist($coach2);
            $entityManager->flush();
    }
        $form = $this->createForm(CommentResaType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($coach as $comment) {
                $commentaire = $form['commentaire']->getData();
                $comment->setCommentaire($commentaire);
                $entityManager->persist($comment);
            }
            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('home');
        }
        return $this->render('panier/validationreservation.html.twig', [
            'form'=> $form->createView(),
        ]);
    }
}

