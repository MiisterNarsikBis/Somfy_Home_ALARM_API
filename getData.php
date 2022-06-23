<?php

function getDataMatos() {

    global $site_id, $access_token;

    $arrayClean = [];


    if(isset($site_id) && $site_id != "") {
        $infos_devices = "https://api.myfox.io/v3/site/".$site_id."/device?access_token=".$access_token;
        $details_devices = json_decode(file_get_contents($infos_devices));
        foreach($details_devices->items as $item) {

            $arrayClean[] = [
                'device_id' => $item->device_id,
                'nom' => $item->label,
                'type' => $item->device_definition->label,
                'typeSomfy' => $item->device_definition->type,
                'status' => array(
                    'shutter_state' => $item->status->shutter_state ?? null,
                    'wifi_level_percent' => $item->status->wifi_level_percent ?? null,
                    'rlink_quality_percent' => $item->status->rlink_quality_percent ?? null,
                    'power_state' => $item->status->power_state ?? null,
                    'batterie' => $item->status->battery_level ?? null,
                    'lastUpdate' => generateDate($item->status->last_status_at),
                    'temperature' => $item->status->temperature ?? null
                )
            ];
        }
    }

    return $arrayClean;
}

function getDataCalendar() {

    global $site_id, $access_token;

    $arrayClean = [];

    if(isset($site_id) && $site_id != "") {
        $infos = "https://api.myfox.io/v3/site/".$site_id."/scenario?access_token=".$access_token;
        $details_infos = json_decode(file_get_contents($infos));

        foreach($details_infos->items as $item) {
            $arrayClean[] = array(
                'scenario_id' => $item->scenario_id,
                'time' => $item->time,
                'action' => $item->security_level,
                'jours' => (array)$item->days,
                'actif' => $item->enabled
            );
        }
    }

    return $arrayClean;
}

function getInfoAlarm() {

    global $site_id, $access_token;

    $arrayClean = [];

    if(isset($site_id) && $site_id != "") {
        $infos = "https://api.myfox.io/v3/site/".$site_id."?access_token=".$access_token;
        $details_infos = json_decode(file_get_contents($infos));

        $arrayClean = array(
            'nom' => $details_infos->name,
            'address1' => $details_infos->address1,
            'address2' => $details_infos->address2,
            'codePostal' => $details_infos->zip_code,
            'ville' => $details_infos->city,
            'lat' => $details_infos->latitude,
            'lng' => $details_infos->longitude,
            'alarmeActuel' => $details_infos->security_level
        );
    }

    return $arrayClean;
}

function getHistorique() {

    global $site_id, $access_token;

    $arrayClean = [];

    $history = "https://api.myfox.io/v3/site/".$site_id."/history?access_token=".$access_token;
    $details_history = json_decode(file_get_contents($history));

    /*
        Message disponible :
        home_activity -> Entrée / sortie
        security_level -> Les activations / désactivations manuel
        device_diagnosis -> info sur les piles,
        device_config -> Changement de configuration,
        calendar -> Action du calendrier



     */
    $messageTypeAllowed = ['home_activity', 'security_level', 'calendar'];

    foreach ($details_history->items as $item) {

        if(in_array($item->message_type, $messageTypeAllowed)) {
            //Traitement uniquement des messages choisi audessus ;)

            $arrayClean[] = array(
                'message_type' => $item->message_type,
                'message_key' => $item->message_key,
                'userDsp' => $item->message_vars->userDsp ?? null,
                'occurred_at' => $item->occurred_at
            );

        }

    }

    return $arrayClean;
}

if(isset($_GET['action']) && $_GET['action'] == "scenario") {

    if(isset($_GET['id']) && isset($_GET['enabled'])){

        $data = array(
            'enabled' => filter_var($_GET['enabled'], FILTER_VALIDATE_BOOL)
        );

        $data_json = json_encode($data);


        $response = generateCurl("https://api.myfox.io/v3/site/".$site_id."/scenario/".$_GET['id']."?access_token=".$access_token, $data_json, 'PUT');

    }

}

if(isset($_GET['action']) && $_GET['action'] == "shutter_state") {

    if(isset($_GET['id']) && isset($_GET['type'])) {
        $data = array(
                "action" => $_GET['type']
            );

        $data_json = json_encode($data);

        $response = generateCurl("https://api.myfox.io/v3/site/".$site_id."/device/". $_GET['id']."/action?access_token=".$access_token, $data_json, 'POST');
    }

}

