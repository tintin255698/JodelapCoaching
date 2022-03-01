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
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;


class PanierController extends AbstractController
{
    /**
     * @Route("/commande", name="panier")
     */
    public function index(CoffretRepository $coffretRepository, EntityManagerInterface $entityManager, SessionInterface $session, EvenementRepository $evenementRepository, CoachingTarifRepository $coachingTarifRepository, UserRepository $userRepository): Response
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
           foreach ($coffretWithData as $item) {
               $totalItem3 = $item['product']->getPrix() * $item['quantity']['heure'];
               $totalCoffret += $totalItem3;
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
               $find = $coachingTarifRepository->find($id);
               if($quantity['personne'] >2){
                   $prix = $find->getPriceForTwo()+($find->getPriceForThree()*($quantity['personne']-2));
               }else{
                   $prix =  $find->getPriceForTwo();
               }
               $coachingWithData[] = [
                   'product' => $find,
                   'quantity' => $quantity['quantity'],
                   'personne' => $quantity['personne'],
                   'prix' => $prix
               ];
           }

//Calcul total

           $sum = array_sum(array_column($coachingWithData, 'prix'));

//Total general


           $tot = ($sum + $totalEvenement/100 + $totalCoffret/100)*100;


           $tva = $tot*0.2;


           $debut = new DateTime('now');
           $uniq = $debut->format('dmY') . '-' . uniqid();

       }

        return $this->render('panier/index.html.twig', [
            'evenement' => $evenementWithData,
            'coaching' => $coachingWithData,
            'totalEvenement' => $totalEvenement,
            'sum' => $sum,
            'coffret'=>$coffretWithData,
            'totalCoffret' =>$totalCoffret,
            'total' => $tot,
            'tva'=>$tva,
            'uniq'=> $uniq,
        ]);
    }

    /**
     * @Route("/confirmationdevoscoordonnees/{uniq}", name="confirmationCoordonee")
     */
    public function confirmationCoordonnee($uniq, Request $request, EntityManagerInterface $entityManager, CoffretRepository $coffretRepository, SessionInterface $session, EvenementRepository $evenementRepository, CoachingTarifRepository $coachingTarifRepository): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('info', "Vous devez être connecté");
            return $this->redirectToRoute('app_login');
        }else {
            $user = $this->getUser();

            $form = $this->createForm(CoordonneesType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $info = $form['information']->getData();
                $entityManager->persist($user);
                $entityManager->flush();

                //Récupère les paniers
                $coffret = $session->get('coffret', []);
                $evenement = $session->get('evenement', []);
                $coaching = $session->get('coaching', []);

                $session = new Session();
                $session->set('info', $info);
                $session->get('info');

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
                foreach ($coffretWithData as $item) {
                    $totalItem3 = $item['product']->getPrix() * $item['quantity']['heure'];
                    $totalCoffret += $totalItem3;
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
                    $find = $coachingTarifRepository->find($id);
                    if($quantity['personne'] >2){
                        $prix = $find->getPriceForTwo()+($find->getPriceForThree()*($quantity['personne']-2));
                    }else{
                        $prix =  $find->getPriceForTwo();
                    }
                    $coachingWithData[] = [
                        'product' => $find,
                        'quantity' => $quantity['quantity'],
                        'personne' => $quantity['personne'],
                        'prix' => $prix
                    ];
                }

//Calcul total

                $sum = array_sum(array_column($coachingWithData, 'prix'));

//Total general


                $tot = ($sum + $totalEvenement/100 + $totalCoffret/100)*100;


                $tva = $tot*0.2;


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
                            $coachingData->setQuantityEvent($evenement['quantity']);
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
                            $coachingData->setPrix($totalCoffret);
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
                            $coachingData->setNbPersonne($data['personne']);
                            $coachingData->setResaConfirm(0);
                            $coachingData->setPrix($data['prix']);
                            $entityManager->persist($coachingData);
                            $entityManager->flush();
                        }
                    }
                }

                return $this->redirectToRoute('attenteConfirmation', ['commande' => $uniq]);
            }
        }



        return $this->render('panier/confirmationCoordonnee.html.twig', [
                'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/attenteconfirmationcommande/{commande}", name="attenteConfirmation")
     */
    public function detail (ResaCoachingRepository $resaCoachingRepository): Response
    {
        $user = $this->getUser();
        $lastResa = $resaCoachingRepository->derniereCommande($user);
        $commande = $lastResa[0]['numeroDeCommande'];


        if (!$this->getUser()) {
            $this->addFlash('info', "Vous devez être connecté");
            return $this->redirectToRoute('app_login');
        }else {
            $resa = $resaCoachingRepository->findOneBy(['numeroDeCommande' => $commande]);
        }
        return $this->render('panier/confirmation.html.twig', [
            'resa' => $resa
        ]);
    }

    /**
     * @Route("/validationreservation/{numeroDeCommande}", name="validationConfirmation")
     */
    public function validation( CoffretRepository $coffretRepository, SessionInterface $session, EvenementRepository $evenementRepository, CoachingTarifRepository $coachingTarifRepository, UserRepository $userRepository, $numeroDeCommande, ResaCoachingRepository $resaCoachingRepository, EntityManagerInterface $entityManager, Request $request, MailerInterface $mailer): Response
    {
        $coach = $resaCoachingRepository->findBy(['numeroDeCommande' => $numeroDeCommande]);

        $numCommande = $resaCoachingRepository->findOneBy(['numeroDeCommande' => $numeroDeCommande]);


        if ($this->getUser()->getId() != $coach[0]->getUser()->getId()){
            return $this->redirectToRoute('panier');
        } else {

      $commande = $coach[0]->getNumeroDeCommande();

            //Récupère les paniers
            $coffret = $session->get('coffret', []);
            $evenement = $session->get('evenement', []);
            $coaching = $session->get('coaching', []);
            $info = $session->get('info');



//coffret
            $coffretWithData = [];


//Transforme le panier en array
            foreach ($coffret as $id => $quantity) {
                $coffretWithData[] = [
                    'product' => $coffretRepository->find($id),
                    'quantity' => $quantity,
                    'information'=>$quantity['information']
                ];
            }

//Calcul total
            $totalCoffret = 0;
            foreach ($coffretWithData as $item) {
                $totalItem3 = $item['product']->getPrix() * $item['quantity']['heure'];
                $totalCoffret += $totalItem3;
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
                $find = $coachingTarifRepository->find($id);
                if($quantity['personne'] >2){
                    $prix = $find->getPriceForTwo()+($find->getPriceForThree()*($quantity['personne']-2));
                }else{
                    $prix =  $find->getPriceForTwo();
                }
                $coachingWithData[] = [
                    'product' => $find,
                    'quantity' => $quantity['quantity'],
                    'personne' => $quantity['personne'],
                    'prix' => $prix,
                    'information'=>$quantity['information']
                ];
            }



//Calcul total

            $sum = array_sum(array_column($coachingWithData, 'prix'));

//Total general


            $tot = ($sum + $totalEvenement/100 + $totalCoffret/100)*100;


            $tva = $tot*0.2;



            $email = (new TemplatedEmail())
                        ->from(new Address('jodelap.coaching@gmail.com', 'Jodelap Coaching'))
                        ->to($this->getUser()->getEmail())
                        ->subject('Confirmation de réservation'.' '.$commande)
                        ->context([
                            'evenement' => $evenementWithData,
                            'coaching' => $coachingWithData,
                            'totalEvenement' => $totalEvenement,
                            'sum' => $sum,
                            'coffret'=>$coffretWithData,
                            'totalCoffret' =>$totalCoffret,
                            'total' => $tot,
                            'tva'=>$tva,
                            'commande'=>$commande,
                            'user'=>$this->getUser()
                        ])
                        ->htmlTemplate('panier/emailjodelap.html.twig');
                    $mailer->send($email);

            $email2 = (new TemplatedEmail())
                ->from(new Address('jodelap.coaching@gmail.com', 'site internet Jodelap Coaching'))
                ->to('vivien.joly@hotmail.fr')
                ->subject('Confirmation de réservation'.' '.$commande)
                ->context([
                    'evenement' => $evenementWithData,
                    'coaching' => $coachingWithData,
                    'totalEvenement' => $totalEvenement,
                    'sum' => $sum,
                    'coffret'=>$coffretWithData,
                    'totalCoffret' =>$totalCoffret,
                    'total' => $tot,
                    'tva'=>$tva,
                    'commande'=>$commande,
                    'user'=>$this->getUser(),
                    'info' => $info
                ])
                ->htmlTemplate('panier/emailcoach.html.twig');
            $mailer->send($email2);

            foreach ($coach as $coach2) {
                $coach2->setResaConfirm(1);
                $entityManager->persist($coach2);
                $entityManager->flush();
            }

            if($info) {
                foreach ($coach as $coach2) {
                    $coach2->setCommentaire($info);
                    $entityManager->persist($coach2);
                    $entityManager->flush();
                }
            }

            if(isset($coachingWithData[0]['information'])) {
                foreach ($coach as $coach2) {
                    if ($coachingWithData[0]['information'] and isset($coachingWithData[1]['information']) and isset($coachingWithData[2])) {
                        $coach2->setCommentCoaching($coachingWithData[0]['information'] . ' ' . $coachingWithData[1]['information'] . ' ' . $coachingWithData[2]['information']);
                    } elseif ($coachingWithData[0]['information'] and isset($coachingWithData[1]['information'])) {
                        $coach2->setCommentCoaching($coachingWithData[0]['information'] . ' ' . $coachingWithData[1]['information']);
                    } else {
                        $coach2->setCommentCoaching($coachingWithData[0]['information']);
                    }
                    $entityManager->persist($coach2);
                    $entityManager->flush();
                }
            }


            if(isset($coffretWithData[0]['information'])){
                foreach ($coach as $coach2) {
                    $coach2->setCommentCoffret($coffretWithData[0]['information']);
                    $entityManager->persist($coach2);
                    $entityManager->flush();
                }
            }

            if($mailer) {
                $this->get('session')->remove('coffret');
                $this->get('session')->remove('evenement');
                $this->get('session')->remove('coaching');
            }



        }
        return $this->render('panier/validationreservation.html.twig', [
            'commande' => $numCommande
        ]);
    }
}

