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
            require ('panier.php');
        }

        return $this->render('panier/index.html.twig', [
            'evenement' => $evenementWithData,
            'coaching' => $coachingWithData,
            'totalEvenement' => $totalEvenement,
            'sum' => $sum,
            'coffret'=>$coffretWithData,
            'totalCoffret' =>$totalCoffret,
            'total' => $tot,
            'tva'=>$tva
        ]);
    }

    /**
     * @Route("/confirmationdevoscoordonnees", name="confirmationCoordonee")
     */
    public function confirmationCoordonnee(Request $request, EntityManagerInterface $entityManager, CoffretRepository $coffretRepository, SessionInterface $session, EvenementRepository $evenementRepository, CoachingTarifRepository $coachingTarifRepository): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('info', "Vous devez être connecté");
            return $this->redirectToRoute('app_login');
        }else {
            $user = $this->getUser();

            $form = $this->createForm(CoordonneesType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($user);
                $entityManager->flush();

                require('panier.php');

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

                return $this->redirectToRoute('attenteConfirmation');
            }
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
        if (!$this->getUser()) {
            $this->addFlash('info', "Vous devez être connecté");
            return $this->redirectToRoute('app_login');
        }else {

            $user = $this->getUser();

            $lastResa = $resaCoachingRepository->derniereCommande($user);

            $commande = $lastResa[0]['numeroDeCommande'];

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

    require('panier.php');


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
                ->to('jodelap.coaching@gmail.com')
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
                ->htmlTemplate('panier/emailcoach.html.twig');
            $mailer->send($email2);


if($mailer) {
    $this->get('session')->remove('coffret');
    $this->get('session')->remove('evenement');
    $this->get('session')->remove('coaching');
}

            foreach ($coach as $coach2) {
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
                    $entityManager->flush();
                }
                $this->addFlash('success', 'Votre message a bien été envoyé');
                return $this->redirectToRoute('home');
            }
        }
        return $this->render('panier/validationreservation.html.twig', [
            'form'=> $form->createView(),
            'commande' => $numCommande
        ]);
    }
}

