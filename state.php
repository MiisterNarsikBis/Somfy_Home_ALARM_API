<?php

require_once(__DIR__ . '/include.php');

if($site_id == null) {
    header("Refresh:10");
}

$url_state = "https://api.myfox.io/v3/site/".$site_id."?access_token=".$access_token;
$state = json_decode(file_get_contents($url_state));
$render = false;

$history = "https://api.myfox.io/v3/site/".$site_id."/history?access_token=".$access_token;
$details_history = json_decode(file_get_contents($history));

$arrayMessageKey = ['calendar', 'security_level'];

$lastInfo = [];


foreach ($details_history->items as $item) {

    if(in_array($item->message_type, $arrayMessageKey) == true){
        $lastInfo = (array)$item;
        break;
    }

}

if(!empty($lastInfo)) {

    $lastInfo['occurred_at'] = generateDate($lastInfo['occurred_at']);

}

if(isset($_GET['display'])) {
    $render = true;
}

if($render == false){
    echo json_encode(array('status' => $state->security_level));
    die;
}


?>

<style>
    .stock-widget {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        flex-direction: column;
        flex-wrap: nowrap;
        color: #ffffff;
        font-family: -apple-system, BlinkMacSystemFont, "Open Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        font-weight: 300;
        background: linear-gradient(0deg, black, rgba(0, 0, 0, 0.75));
        border-radius: 1rem;
        width: 400px;
        height: 300px;
        box-shadow: 0px 8px 38px -8px rgba(0, 0, 0, 0.75);
    }
    .stock-widget .info {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: row;
        flex-wrap: nowrap;
        padding-left: 1.5rem;
        padding-right: 1.5rem;
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
        width: 86.95%;
    }
    .stock-widget .info .name {
        margin-right: auto;
    }
    .stock-widget .info .fullname {
        font-size: 0.8rem;
        opacity: 0.7;
    }
    .stock-widget .badge {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: row;
        flex-wrap: nowrap;
        width: 110%;
        height: 45%;
        background: linear-gradient(0deg, #4cd964, #76e288);
        border-radius: 1rem;
        box-shadow: 0px 8px 18px -7px rgba(0, 0, 0, 0.75);
    }
    .stock-widget .badge .value {
        font-size: 2.2rem;
    }
    .stock-widget .badge .currency {
        font-size: 1.8rem;
    }
    .stock-widget .more-data {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: row;
        flex-wrap: nowrap;
        padding-left: 1.5rem;
        padding-right: 1.5rem;
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
        width: 86.95%;
    }
    .stock-widget .more-data .loss {
        color: #ff2d55;
    }
    .stock-widget .more-data .earn {
        color: #4cd964;
    }
    .stock-widget .more-data .change {
        margin-right: auto;
    }
    .stock-widget .more-data .change,
    .stock-widget .more-data .change-percentage {
        opacity: 0.7;
    }

    body {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        flex-wrap: nowrap;
        background-color: #dcdcde;
        min-height: 100vh;
    }

    footer {
        position: fixed;
        bottom: 2rem;
        font-family: -apple-system, BlinkMacSystemFont, "Open Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
    }
    footer a {
        color: #000000;
        text-decoration: none;
        border-bottom: #000000 2px dashed;
    }
    footer a:hover {
        color: #ffffff;
        background: #000000;
    }
</style>

<div class="stock-widget">
    <div class="info">
        <div class="name" style="font-weight: bold">
            ALARME MAISON
        </div>
    </div>

    <?php if($state->security_level == "partial") : ?>
        <div class="badge" style="background: linear-gradient(0deg, #ffeb3b, #dfc905);">
            <span class="value" style="color:black">Mode Nuit</span>
        </div>
    <?php elseif($state->security_level == "armed") : ?>
        <div class="badge">
            <span class="value">Alarme complète</span>
        </div>
    <?php elseif($state->security_level == "disarmed") : ?>
        <div class="badge" style="background: linear-gradient(0deg, #e91e63, #990739);">
            <span class="value" >Alarme Désactivée</span>
        </div>
    <?php endif; ?>

    <div class="more-data">
        <div class="change earn">
            <?php if(!empty($lastInfo)) : ?>
                <?= $lastInfo['occurred_at']->format('H:i'); ?>
            <?php endif; ?>
        </div>
        <div class="change-percentage earn">
            <?php if(!empty($lastInfo)) :

                if(isset($lastInfo['message_vars']->userDsp)){
                    echo $lastInfo['message_vars']->userDsp;
                }else{
                    echo "Calendrier";
                }

            endif; ?>
        </div>
    </div>
</div>
