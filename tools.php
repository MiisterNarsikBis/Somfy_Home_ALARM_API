<?php

function updateConfig($var, $val) {
    $config = file_get_contents(__DIR__.'/private/config.json');
    $config = json_decode($config, true);
    $config[$var] = $val;
    $config = json_encode($config);
    file_put_contents(__DIR__.'/private/config.json', $config);
}

function getConfig($var) {
    $config = file_get_contents(__DIR__.'/private/config.json');
    $config = json_decode($config, true);

    if(!isset($config[$var])){
        return null;
    }

    return $config[$var];
}

if(isset($_GET['gestionCron']) && $_GET['gestionCron'] == "shutterCamera") {
    if(getConfig('shutterCamera') == true) {
        updateConfig('shutterCamera', false);
    } else {
        updateConfig('shutterCamera', true);
    }
}

if(isset($_GET['gestionCron']) && $_GET['gestionCron'] == "chauffage") {
    if(getConfig('chauffage') == true) {
        updateConfig('chauffage', false);
    } else {
        updateConfig('chauffage', true);
    }
}
