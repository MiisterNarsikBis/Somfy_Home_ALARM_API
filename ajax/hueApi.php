<?php

require_once(__DIR__.'/../include.php');
const TOKEN = "TOKEN GENERER VIA LE HUB";
const URL = "URL VERS LE HUB HUE PHILLIPS";

function getAPIPhilips($type = "GET", $url = null, $data = []) {

    if($type == "GET"){
        $ch = curl_init();


        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds


        // Récupérer le contenu de la page
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );


        //Désactiver la vérification du certificat puisque waytolearnx utilise HTTPS
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //Exécutez la requête
        $result = curl_exec($ch);
        //Afficher le résultat
        return json_decode($result);

        curl_close($ch);
    }elseif($type == "PUT") {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response);
    }

    return json_encode(array("error" => "error"));
}

$colors = [
    'startlight' => [
        'x' => [0.2207, 0.4244, 0.3146],
        'y' => [0.1346, 0.336, 0.2069]
    ],
    'nebula' => [
        'x' => [0.2002, 0.3745, 0.2729],
        'y' => [0.0893, 0.273, 0.1728]
    ],
    'aurore' => [
        'x' => [0.1541, 0.2351, 0.1584],
        'y' => [0.0833, 0.3862, 0.1507]
    ],
    'random' => [
        'x' => [(rand(0, 10000)/10000), (rand(0, 10000)/10000), (rand(0, 10000)/10000)],
        'y' => [(rand(0, 10000)/10000), (rand(0, 10000)/10000), (rand(0, 10000)/10000)]
    ]
];

function getLight($light){

    $lights = [$light];

    if($light == 50){
        $lights = [3,4,5];
    }

    if($light == 51){
        $lights = [8,9,10];
    }

    return $lights;

}

function getNewBri($bri, $signe, $val = 10){

    if($signe == "plus"){
        $bri = $bri + $val;
        echo "+";
    }elseif($signe == "moins"){
        $bri = $bri - $val;
        echo "-";
    }

    if($bri <= 0) {
        $bri = 1;
    }elseif($bri >= 254) {
        $bri = 254;
    }

    return $bri;

}

function changeColor($light, $nameColor){

    $lights = getLight($light);

    global $colors;

    if(array_key_exists($nameColor, $colors)){

        dump($colors[$nameColor]);

        $i = 0;

        foreach($lights as $light){
            getAPIPhilips('PUT', URL."lights/".$light."/state", ['xy' => [$colors[$nameColor]['x'][$i], $colors[$nameColor]['y'][$i]]]);
            $i++;
        }

    }

}


//Light 1 = Chambre
//Light 2 = Bureau

// -- Light 50 = Salon Groupé -- //
//Light 3 = Ampoule Entree
//Light 4 = Ampoule une
//Light 5 = Ampoule deux

//Light 6 = Couloir

//Light 7 = Prise Garage

// -- Light 51 = Hue Play Groupé -- //
//Light 8 = Hue Play G
//Light 9 = Hue Play D
//Light 10 = Hue Play M


if(isset($_GET['eteindre']) && $_GET['eteindre'] != ""){
    //eteindre light
    $lights = getLight($_GET['eteindre']);
    foreach($lights as $l){
        getAPIPhilips('PUT', URL."lights/".$l."/state", ['on' => false]);
    }
    if(isset($_GET['force'])){
        header("Location: hue.php?force");
        exit;
    }
    header("Location: hue.php");
    exit;
}

if(isset($_GET['allumer']) && $_GET['allumer'] != ""){
    //eteindre light
    $lights = getLight($_GET['allumer']);
    foreach($lights as $l){
        getAPIPhilips('PUT', URL."lights/".$l."/state", ['on' => true]);
    }
    header("Location: hue.php");

}


if(isset($_GET['luminosite']) && $_GET['luminosite'] != "") {
    //Gestion luminosité lumière TODO: PLUS TARD PEUT ETRE 
}
