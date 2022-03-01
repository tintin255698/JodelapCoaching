<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\EvenementRepository;
use App\Repository\ImageRepository;
use App\Form\contactType;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, ImageRepository $imageRepository, CommentRepository $commentRepository, MailerInterface $mailer, EvenementRepository $evenementRepository): Response
    {
        $image = $imageRepository->imageHome();
        $commentaire = $commentRepository->commentaire();


        $form = $this->createForm(contactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new Email())
                ->from($form['email']->getData())
                ->to('jodelap.coaching@gmail.com')
                ->subject($form['sujet']->getData())
                ->text($form['message']->getData());

            $mailer->send($email);
        }

        $evenementStar = $evenementRepository->evenementAccueil();


        return $this->render('home/index.html.twig', [
            'image' => $image,
            'commentaire' => $commentaire,

            'form' =>$form->createView(),
            'event' =>$evenementStar,
        ]);
    }
}