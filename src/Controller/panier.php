<?php

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
foreach ($coffretWithData as $item3) {
    $totalItem = $item3['product']->getPrix() * $item3['quantity']['heure'];
    $totalCoffret += $totalItem;
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



?>