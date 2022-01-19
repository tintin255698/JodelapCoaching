<?php

namespace App\Controller;


use App\Repository\CommentRepository;
use App\Repository\ImageRepository;
use App\Form\contactType;
use App\Repository\UserRepository;
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
    public function index(ImageRepository $imageRepository, CommentRepository $commentRepository): Response
    {
        $image = $imageRepository->imageHome();
        $commentaire = $commentRepository->commentaire();

        return $this->render('home/index.html.twig', [
            'image' => $image,
            'commentaire' => $commentaire
        ]);
    }

}