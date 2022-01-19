<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    /**
     * @Route("/galerie", name="galerie")
     */
    public function index(ImageRepository $imageRepository): Response
    {
        $video = $imageRepository->findByType('video');
        $image = $imageRepository->findByType('image');

        return $this->render('image/index.html.twig', [
            'image' => $image,
            'video' => $video,
        ]);
    }
}
