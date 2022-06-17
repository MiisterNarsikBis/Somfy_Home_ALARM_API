<?php

function getDataMatos() {

    global $site_id, $access_token;

    $arrayClean = [];

    $infos_devices = "https://api.myfox.io/v3/site/".$site_id."/device?access_token=".$access_token;
    $details_devices = json_decode(file_get_contents($infos_devices));
    foreach($details_devices->items as $item) {

        $arrayClean[] = [
            'nom' => $item->label,
            'type' => $item->device_definition->label,
            'status' => array(
                'batterie' => isset($item->status->battery_level) ? $item->status->battery_level : null,
                'lastUpdate' => generateDate($item->status->last_status_at),
                'temperature' => isset($item->status->temperature) ? $item->status->temperature : null
            )
        ];
    }

    return $arrayClean;
}

function getDataCalendar() {

    global $site_id, $access_token;

    $arrayClean = [];

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

