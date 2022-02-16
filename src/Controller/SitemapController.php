<?php

namespace App\Controller;


use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     */
    public function index(Request $request, EvenementRepository $evenementRepository): Response
    {
        $hostname = $request->getSchemeAndHttpHost();
        $urls = [];
        $urls[] = ['loc' => $this->generateUrl('home')];
        $urls[] = ['loc' => $this->generateUrl('evenement')];
        $urls[] = ['loc' => $this->generateUrl('coaching')];
        $urls[] = ['loc' => $this->generateUrl('coffret')];
        $urls[] = ['loc' => $this->generateUrl('galerie')];
        $urls[] = ['loc' => $this->generateUrl('avis')];
        $urls[] = ['loc' => $this->generateUrl('panier')];
        $urls[] = ['loc' => $this->generateUrl('app_register')];
        $urls[] = ['loc' => $this->generateUrl('app_login')];
        $urls[] = ['loc' => $this->generateUrl('app_logout')];
        $urls[] = ['loc' => $this->generateUrl('user')];


        foreach ($evenementRepository->findAll() as $evenement) {

            $images = [
                'loc' => '/uploads/evenement/'.$evenement->getImage(), // URL to image
                'title' => $evenement->getTitre()    // Optional, text describing the image
            ];

            $urls[] = [
                'loc' => $this->generateUrl('evenement_detail', [
                    'slug' => $evenement->getSlug(),
                ]),
                'image' =>$images,
                'priority'=>1
            ];
        }

        $response = new Response(
            $this->renderView('sitemap/index.html.twig', ['urls' => $urls,
                'hostname' => $hostname]),
            200
        );

// Ajout des entêtes
        $response->headers->set('Content-Type', 'text/xml');

// On envoie la réponse
        return $response;
    }
}
