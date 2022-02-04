<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\EvenementRepository;
use App\Repository\HeaderRepository;
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
    public function index(Request $request, ImageRepository $imageRepository, CommentRepository $commentRepository, HeaderRepository $headerRepository, MailerInterface $mailer, EvenementRepository $evenementRepository): Response
    {
        $image = $imageRepository->imageHome();
        $commentaire = $commentRepository->commentaire();
        $header= $headerRepository->findAll();

        $form = $this->createForm(contactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new Email())
                ->from($form['email']->getData())
                ->to('jodelap.coaching@gmail.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject($form['sujet']->getData())
                ->text($form['message']->getData());

            $mailer->send($email);
        }

        $evenementStar = $evenementRepository->findBy([], ['id'=>'DESC'], [3]);

        return $this->render('home/index.html.twig', [
            'image' => $image,
            'commentaire' => $commentaire,
            'header' => $header,
            'form' =>$form->createView(),
            'event' =>$evenementStar,
        ]);
    }
}