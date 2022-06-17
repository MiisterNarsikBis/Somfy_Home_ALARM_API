<?php

$log_level = 1;
$config = parse_ini_file('private/config.ini');
$username = $config['username'];
$password = $config['password'];
$client_id = $config['client_id'];
$client_secret = $config['client_secret'];
$access_token = isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : null;
$refresh_token = isset($_SESSION["refresh_token"]) ? $_SESSION["refresh_token"] : null;

if ($_SESSION["site_id"] == null && $access_token != null){
        $infos_user = "https://api.myfox.io/v3/user?access_token=".$access_token;
        $details_user = json_decode(file_get_contents($infos_user));
        $delais_activation = ($details_user->sites[0]->exit_delay)+5; #delais d'activation de votre alarme +5 secs
        $_SESSION["site_id"] = $details_user->sites[0]->site_id;
}
$site_id = isset($_SESSION["site_id"]) ? $_SESSION["site_id"] : null;

if (isset($_SESSION["device_id"]) && $_SESSION["device_id"] == null && $access_token != null){
$infos_devices = "https://api.myfox.io/v3/site/".$site_id."/device?access_token=".$access_token;
$details_devices = json_decode(file_get_contents($infos_devices));
        foreach($details_devices->items as $item)
        {
                if ($item->device_definition->device_definition_id  == "mss_siren"){
                        $_SESSION["device_id"] = $item->device_id;
                        $_SESSION["label_siren"] = $item->label;
                }
        }
}


?>
