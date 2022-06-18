<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if(is_dir('vendor/')){
    require_once('vendor/autoload.php');
}
require_once('parametres.php');

define("HTTP", $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/somfy');

if(isset($_POST['passwordPanel'])) {


    if($_POST['passwordPanel'] != $passwordPanel){
        //Mauvais MDP;
        header("Location: ". HTTP ."/password.php?mauvais");
        exit;
    }else{
        $_SESSION['passwordPanel'] = $_POST['passwordPanel'];
        header("Location: ". HTTP);
        exit;
    }

}


if(!isset($_SESSION['passwordPanel'])) {
    header("Location: ". HTTP ."/password.php?nopass");
    exit;
}else{
    if(isset($passwordPanel) && $_SESSION['passwordPanel'] != $passwordPanel) {
        header("Location: ". HTTP ."/password.php?mauvais");
        exit;
    }
}


if (isset($log_level) && $log_level == 1) {
    $log = date("F j, Y, g:i a").PHP_EOL;
}

function getTranslateDay($day) {

    switch ($day){
        case 'mo':
            return 'Lundi';
        case 'tu' :
            return 'Mardi';
        case 'we' :
            return 'Mercredi';
        case 'th' :
            return 'Jeudi';
        case 'fr' :
            return 'Vendredi';
        case 'sa' :
            return 'Samedi';
        default:
            return 'Dimanche';
    }

}

function translateSecurityLevel($security_level) {

    if($security_level == "partial") {
        return 'Mode nuit';
    }elseif($security_level == "armed") {
        return 'Alarme complète';
    }elseif($security_level == "disarmed") {
        return 'Désactiver l\'alarme';
    }

    return $security_level;

}

function translateSecurityLevelDisplay($security_level) {

    if($security_level == "partial") {
        return 'Mode nuit';
    }elseif($security_level == "armed") {
        return 'Alarme complète';
    }elseif($security_level == "disarmed") {
        return 'Alarme désactivée';
    }

    return $security_level;

}


function getColorBatterie($int) {
    if($int >= 50) {
        return '#1cc88a';
    }elseif($int >= 20) {
        return '#f6c23e';
    }else {
        return '#e74a3b';
    }
}

function generateDate($date) {
    $newDate = new DateTime($date, new DateTimeZone('UTC'));
    $newDate->setTimezone(new DateTimeZone('Europe/Paris'));

    return $newDate;
}

function generateCurl($url, $data_json, $typeRequest = "POST") {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $typeRequest);
    if($data_json != null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    curl_close($ch);

    return $response;
}

function new_token($client_id,$client_secret,$password,$username) {
    global $log,$log_level;

    $data = array(
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'grant_type' => "password",
        'password' => $password,
        'username' => $username
    );

    $data_json = json_encode($data);

    $response = generateCurl('https://sso.myfox.io/oauth/oauth/v2/token', $data_json);

    $_SESSION["access_token"] = substr(preg_split("/:|,/",substr($response, 1, -1))[1],1,-1);
    $_SESSION["refresh_token"] = substr(preg_split("/:|,/",substr($response, 1, -1))[9],1,-1);

    if ($log_level == 1) {
        $log .= "new token ok".PHP_EOL;
    }
    return $log;
}

function refresh_token($client_id,$client_secret,$refresh_token) {
    global $log_level,$log;

    $data = array(
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'grant_type' => "refresh_token",
        'refresh_token' => $refresh_token
    );

    $data_json = json_encode($data);

    $response = generateCurl("https://sso.myfox.io/oauth/oauth/v2/token", $data_json);

    if(strpos($response,"invalid") != false){
        return "erreur";
    } else {
        $_SESSION["access_token"]= substr(preg_split("/:|,/",substr($response, 1, -1))[1],1,-1);
        $_SESSION["refresh_token"] = substr(preg_split("/:|,/",substr($response, 1, -1))[9],1,-1);

        if ($log_level == 1) {
            $log .= "refresh ok".PHP_EOL;
        }
        return $log;
    }
}

include_once('getData.php');