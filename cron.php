<?php

require_once(__DIR__ . '/include.php');
require_once(__DIR__ . '/ajax/hueApi.php');

if(isset($_GET['action']) && $_GET['action'] == "shutterCamera" && getConfig('shutterCamera') == false) {

    // Garage : 7gfXZn7qOAfNa9cqbYfq4zgk4mmpHmUa;
    // Salon : oD4VSwslLfKupssM4y6UskJN4DxjAPEG
    $cameras = ['oD4VSwslLfKupssM4y6UskJN4DxjAPEG'];//, '7gfXZn7qOAfNa9cqbYfq4zgk4mmpHmUa'];

    foreach($cameras as $camera) {

        if(isset($_GET['type'])) {
            $data = array(
                    "action" => $_GET['type']
                );

            $data_json = json_encode($data);

            $response = generateCurl("https://api.myfox.io/v3/site/".$site_id."/device/". $camera."/action?access_token=".$access_token, $data_json, 'POST');

        }

    }



}

if(isset($_GET['gestionChauffage']) && getConfig('chauffage') == false) {

    //Recuperation des informations de température
    if($_GET['gestionChauffage'] == "auto"){

        $temperature = [];

        foreach (getDataMatos() as $materiel) {

            if(isset($materiel['status']['temperature']) && $materiel['status']['temperature'] != null) {
                array_push($temperature, $materiel['status']['temperature']);
            }

        }

        $temp = array_sum($temperature) / count($temperature);

        //prise garage (use en tant que chauffage) = 7


        if($temp < 21) {

            $lights = getLight(7);
            foreach($lights as $l){
                getAPIPhilips('PUT', URL."lights/".$l."/state", ['on' => true]);
            }

        }else {

            $lights = getLight(7);
            foreach($lights as $l){
                getAPIPhilips('PUT', URL."lights/".$l."/state", ['on' => false]);
            }

        }

        $infoLight = getAPIPhilips('GET', URL."lights/7");

        $allumer = $infoLight->state->on == true ? 'Oui' : 'Non';

        echo json_encode(['temperature_moyenne' => $temp, 'alimentation' => $allumer]);
    }

}
