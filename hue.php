<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include(__DIR__ . '/ajax/hueApi.php');

if(isset($_GET['force'])) {
    $lights = [
        [ 'idAction' => 1, 'id' => [1], 'nom' => 'Chambre' ],
        [ 'idAction' => 2, 'id' => [2], 'nom' => 'Bureau' ],
        [ 'idAction' => 3, 'id' => [3], 'nom' => 'Ampoule Entree' ],
        [ 'idAction' => 4, 'id' => [4], 'nom' => 'Ampoule une' ],
        [ 'idAction' => 5, 'id' => [5], 'nom' => 'Ampoule deux' ],
        [ 'idAction' => 6, 'id' => [6], 'nom' => 'Couloir' ],
        [ 'idAction' => 7, 'id' => [7], 'nom' => 'Prise Garage' ],
        [ 'idAction' => 8, 'id' => [8], 'nom' => 'Hue Play G' ],
        [ 'idAction' => 9, 'id' => [9], 'nom' => 'Hue Play D' ],
        [ 'idAction' => 10, 'id' => [10], 'nom' => 'Hue Play M' ],
        [ 'idAction' => 50, 'id' => getLight(50), 'nom' => 'Salon Groupé' ],
        [ 'idAction' => 51, 'id' => getLight(51), 'nom' => 'Hue Play Groupé' ]
    ];
}else{
    $lights = [
        [ 'idAction' => 1, 'id' => [1], 'nom' => 'Chambre' ],
        [ 'idAction' => 2, 'id' => [2], 'nom' => 'Bureau' ],
        [ 'idAction' => 6, 'id' => [6], 'nom' => 'Couloir' ],
        [ 'idAction' => 7, 'id' => [7], 'nom' => 'Prise Garage' ],
        [ 'idAction' => 50, 'id' => getLight(50), 'nom' => 'Salon Groupé' ],
        [ 'idAction' => 51, 'id' => getLight(51), 'nom' => 'Hue Play Groupé' ]
    ];
}


function getPourcent($val1, $val2 = 254) {

    return round(($val1 * 100) / $val2);

}





?>

<link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />


<div class="container">
    <div class="row">
        <?php foreach ($lights as $light) { ?>

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">

                <div class="tile">
                    <div class="wrapper">
                        <div class="header"><strong>Lumière <?= $light['nom'] ?></strong></div>

                        <div class="stats">

                            <?php foreach ($light['id'] as $id) { ?>

                                <div class="stat">
                                    <div class="info">

                                        <?php $infoLight = getAPIPhilips('GET', URL."lights/".$id); ?>

                                        <?php if($infoLight->state->on) { ?>
                                            Allumé <i class="fas fa-lightbulb"></i>
                                        <?php } else { ?>
                                            Eteint <i class="fas fa-lightbulb"></i>
                                        <?php } ?>

                                        <?php if(isset($infoLight->state->bri) && $infoLight->state->on == true) : ?>
                                            <span class="value"><?= getPourcent($infoLight->state->bri) ?> % </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if(isset($infoLight->state->bri) && $infoLight->state->on == true) : ?>

                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" aria-valuenow="<?= getPourcent($infoLight->state->bri) ?>" aria-valuemin="0" aria-valuemax="255" style="width: <?= $infoLight->state->bri ?>%;"></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php } ?>

                        </div>

                        <div class="footer">
                            <?php if($infoLight->state->on == true) : ?>
                                <a href="?eteindre=<?= $light['idAction'] ?><?= isset($_GET['force']) ? '&force' : '' ?>" class="btn btn-primary">Eteindre</a>
                            <?php else : ?>
                                <a href="?allumer=<?= $light['idAction'] ?><?= isset($_GET['force']) ? '&force' : '' ?>" class="btn btn-warning">Allumer</a>
                            <?php endif; ?>
                            <br>

                            <a href="#" class="btn btn-secondary">Max</a>
                            <a href="#" class="btn btn-secondary">+10</a>
                            <a href="#" class="btn btn-secondary">-10</a>
                            <a href="#" class="btn btn-secondary">Min</a>
                        </div>
                    </div>
                    <br>
                </div>

        </div>
        <?php } ?>

    </div>
</div>
