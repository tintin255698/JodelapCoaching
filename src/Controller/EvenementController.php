<?php

namespace App\Controller;

use App\Form\PersonneType;
use App\Repository\EvenementRepository;
use DateInterval;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvenementController extends AbstractController
{
    /**
     * @Route("/evenement", name="evenement")
     */
    public function index(EvenementRepository $evenementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $debutSemaine = new DateTime('now');

        $finSemaine = new DateTime('now');
        $finSemaine->add(new DateInterval('P14D'));

        $evenement = $evenementRepository->findAll();

        $repo = $paginator->paginate(
            $evenement,
            $request->query->getInt('page',1),
            9);

        return $this->render('evenement/index.html.twig', [
            'fin' => $finSemaine,
            'debut' => $debutSemaine,
            'evenement' => $repo,
        ]);
    }

    /**
     * @Route("/evenement/{slug}", name="evenement_detail")
     */
    public function detail($slug, EvenementRepository $evenementRepository): Response
    {
        $now = new DateTime('now');

        $evenementDetails = $evenementRepository->findOneBy(['slug' => $slug]);



        $street = $evenementDetails->getLieu();
        $city = $evenementDetails->getVille();
        $address = $street . ', ' . $city;

        $referer = 'https://nominatim.openstreetmap.org/search?q=' . urlencode($address) . '&format=jsonv2&addressdetails=1&limit=1';
        $opts = array(
            'http' => array(
                'header' => array("Referer: $referer\r\n")
            )
        );
        $context = stream_context_create($opts);
        $myURL = file_get_contents($referer, false, $context);

        $resp = json_decode($myURL, true);

        $lat = $resp[0]['lat'];
        $long = $resp[0]['lon'];

        return $this->render('evenement/detail.html.twig', [
            'evenementDetail' => $evenementDetails,
            'now' => $now,
            'lat' => $lat,
            'long' => $long
        ]);
    }

    /**
     * @Route("/evenement/add/{id}", name="evenement_add")
     */
    public function evenementAdd($id, Request $request)
    {
        $session = $request->getSession();

        $panier = $session->get('evenement', []);

        if(!empty($panier[$id])){
            $panier[$id] ++ ;
        } else {
            $panier[$id] = 1;
        }
        $session->set('evenement', $panier);

        return $this->redirectToRoute('panier');
    }

}

