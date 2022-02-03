<?php

namespace App\Controller;

use App\Entity\ResaCoaching;
use App\Form\CommentResaType;
use App\Repository\CoachingTarifRepository;
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
    public function index(EntityManagerInterface $entityManager, ResaCoachingRepository $resaCoachingRepository, MailerInterface $mailer,SessionInterface $session, EvenementRepository $evenementRepository, CoachingTarifRepository $coachingTarifRepository): Response
    {
       if (!$this->getUser()){
            $this->addFlash('info', "Vous devez être connecté pour accéder au récapitulatif de votre réservation");
            return  $this->redirectToRoute('app_login');
        } else {
            //Récupère le panier
            $evenement = $session->get('evenement', []);
            $coaching = $session->get('coaching', []);

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
            $uniq = $debut->format('dmY').'-'.uniqid();

            //Inscription BDD réservation
            if($coachingWithData or $evenementWithData) {
                $now = new DateTime('now');
                if($evenementWithData) {
                    foreach ($evenementWithData as $evenement) {
                        $evenementId = $evenement['product']->getId();
                        $coachId = $evenementRepository->find($evenementId);
                        $coachingData = new ResaCoaching();
                        $coachingData->setUser($this->getUser());
                        $coachingData->setDateResa($now);
                        $coachingData->setNumeroDeCommande($uniq);
                        $coachingData->setEvenement($coachId);
                        $coachingData->setResaConfirm(0);
                        $coachingData->setPrix($totalItem2/100);
                        $entityManager->persist($coachingData);
                        $entityManager->flush();
                    }
                }
                if($coachingWithData) {
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
        }

        return $this->render('panier/index.html.twig', [
            'evenement' => $evenementWithData,
            'coaching' => $coachingWithData,
            'totalEvenement' => $totalEvenement,
            'totalCoaching' => $totalCoaching,
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
        $this->get('session')->clear();

        $coach = $resaCoachingRepository->findBy(['numeroDeCommande' => $numeroDeCommande]);

        foreach($coach as $coach2){
            $coach2->setResaConfirm(1);
            $entityManager->persist($coach2);
            $entityManager->flush();
    }
//Faire email client et jodelap
        $email = (new Email())
            ->from('jodelap.coaching@gmail.com')
            ->to('vivien.joly@hotmail.fr')
            ->subject('Confirmation de votre réservation')
            ->text('Salut'.'Je vous remercie pour votre confiance ');

        $mailer->send($email);


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

        return $this->render('panier/validationreservation.html.twig', [
            'form'=> $form->createView(),
        ]);
    }
}

