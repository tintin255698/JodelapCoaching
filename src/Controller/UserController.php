<?php

namespace App\Controller;

use App\Entity\MotDePasse;
use App\Entity\User;
use App\Form\CoordonneesType;
use App\Form\MdpType;
use App\Repository\ResaCoachingRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/profil", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/profil/reservation", name="user_reservation")
     */
    public function reservation(ResaCoachingRepository $resaCoachingRepository): Response
    {
        $coaching = $resaCoachingRepository->reservationCoaching($this->getUser());
        $evenement = $resaCoachingRepository->reservationEvenement($this->getUser());
        $coffret = $resaCoachingRepository->reservationCoffret($this->getUser());



        return $this->render('user/reservation.html.twig', [
            'coaching' => $coaching,
            'evenement'=>$evenement,
            'coffret'=>$coffret,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/profil/modifier/coordonnees", name="user_coordonnees")
     */
    public function coordonnes(Request $request, EntityManagerInterface $entityManager): Response
    {
        $id = $this->getUser();

        $form = $this->createForm(CoordonneesType::class, $id);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($id);
            $entityManager->flush();

            $this->addFlash('success', 'Vos coordonnées ont bien été modifiées');
            return $this->redirectToRoute('user');
        }

        return $this->render('user/coordonnees.html.twig', [
           'form' => $form->createView(),
        ]);
    }


    /**
     * @IsGranted("ROLE_USER")
     * @Route("/profil/modifier/motdedepasse", name="user_motdepasse")
     */
    public function newMdp(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder): Response
    {
        $maj = new MotDePasse();

        $user = $this->getUser();

        $form = $this->createForm(MdpType::class, $maj);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(password_verify($maj->getAncienMdp(), $user->getPassword())){
                $nouveau = $maj->getNouveauMdp();
                $password = $encoder->encodePassword($user, $nouveau);

                $user->setPassword($password);

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Votre mot de passe a bien ete modifié !');

                return $this->redirectToRoute('user');

            } else {
                $form->get('ancienMDP')->addError(new FormError("Le mot de passe saisi n'est pas le bon"));
            }
        }

        return $this->render('user/mdp.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/profil/supprimer/{id}", name="user_supprimer")
     */
    public function supprimer(User $id, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($id);
        $this->addFlash('success', 'Merci pour votre confiance et bonne continuation de le milieu du VTT');
        return $this->redirectToRoute('home');
    }





}
