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
foreach ($coffretWithData as $item) {
    $totalItem3 = $item['product']->getPrix() * $item['quantity']['heure'];
    $totalCoffret += $totalItem3;
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
        $find = $coachingTarifRepository->find($id);
        if($quantity['personne'] >2){
            $prix = $find->getPriceForTwo()+($find->getPriceForThree()*($quantity['personne']-2));
        }else{
           $prix =  $find->getPriceForTwo();
        }
        $coachingWithData[] = [
            'product' => $find,
            'quantity' => $quantity['quantity'],
            'personne' => $quantity['personne'],
            'prix' => $prix
        ];
}

//Calcul total

$sum = array_sum(array_column($coachingWithData, 'prix'));

//Total general


$tot = ($sum + $totalEvenement/100 + $totalCoffret/100)*100;


$tva = $tot*0.2;

?>