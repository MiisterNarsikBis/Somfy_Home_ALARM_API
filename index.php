<?php

include(__DIR__ . '/include.php');


$response = generateCurl("https://api.myfox.io/v3/site/".$site_id."?access_token=".$access_token, null);

if ((strpos($response,"unauthorized") != false) || !isset($_SESSION["site_id"]) || $_SESSION["site_id"] != "1") {
    $response = refresh_token($client_id,$client_secret,$refresh_token);

    if ($response == "erreur") {
        $response = new_token($client_id,$client_secret,$password,$username);
        header("Location: ".HTTP);
        exit;
    }
}
//Save string to log, use FILE_APPEND to append.
if ($log_level == 1) {
    $log .= "-------------------------".PHP_EOL;
    file_put_contents('./token.log', $log, FILE_APPEND);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Somfy Accueil</title>

    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <style>
        /* PARTIE INFORMATION */
        .o-progress-circle {
            display: inline-block;
            position: relative;
            width: 50px;
        }
        .o-progress-circle__fill circle {
            fill: none;
            stroke-width: 3px;
            stroke: #858796;
        }
        .o-progress-circle__fill circle:nth-child(2) {
            animation: load-circle 1s;
            stroke: #1cc88a;
            stroke-dasharray: 100;
        }
        .o-progress-circle__number em {
            font-weight: 100;
            font-style: normal;
        }
        .o-progress-circle--rounded circle:nth-child(2) {
            stroke-linecap: round;
        }
        @-moz-keyframes load-circle {
            0% {
                stroke-dashoffset: 100;
            }
        }
        @-webkit-keyframes load-circle {
            0% {
                stroke-dashoffset: 100;
            }
        }
        @-o-keyframes load-circle {
            0% {
                stroke-dashoffset: 100;
            }
        }
        @keyframes load-circle {
            0% {
                stroke-dashoffset: 100;
            }
        }

        /* PARTIE CONTROLE */
        .order-card {
            color: #fff;
        }

        .bg-c-blue {
            background: linear-gradient(45deg,#4099ff,#73b4ff);
        }

        .bg-c-green {
            background: linear-gradient(45deg,#2ed8b6,#59e0c5);
        }

        .bg-c-yellow {
            background: linear-gradient(45deg,#FFB64D,#ffcb80);
        }

        .bg-c-pink {
            background: linear-gradient(45deg,#FF5370,#ff869a);
        }

        .bg-c-grey {
            background: linear-gradient(45deg,#6e707e,#2e303c);
        }

    </style>
    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" />

</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Topbar Search -->

                    <div class="input-group ml-md-3 col-3">
                        <div class="sidebar-brand-icon rotate-n-15">
                            <i class="fas fa-laugh-wink"></i>
                        </div>
                        <div class="sidebar-brand-text mx-3">Somfy &nbsp;<sup>API</sup></div>
                    </div>

                <div class="infoAlarmNow">
                    <?= translateSecurityLevelDisplay(getInfoAlarm()['alarmeActuel']) ?>
                </div>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Contrôle</h1>
                </div>

                <div class="row">
                    <div class="container">
                        <div class="row" style="justify-content: space-around;">

                            <?php

                                $controls = array(
                                    array(
                                        'action' => 'armed',
                                        'color' => 'bg-c-green',
                                        'titre' => 'Activer l\'alarme',
                                    ),
                                    array(
                                        'action' => 'partial',
                                        'color' => 'bg-c-yellow',
                                        'titre' => 'Activer le mode nuit',
                                    ),
                                    array(
                                        'action' => 'disarmed',
                                        'color' => 'bg-c-pink',
                                        'titre' => 'Désactiver l\'alarme',
                                    ),
                                    array(
                                        'action' => 'weekend',
                                        'color' => 'bg-c-blue',
                                        'titre' => 'Mode week-end',
                                    ),
                                    array(
                                        'action' => 'notif_off',
                                        'color' => 'bg-c-grey',
                                        'titre' => 'Désactiver les notifications',
                                    ),
                                    array(
                                        'action' => 'notif_on',
                                        'color' => 'bg-c-grey',
                                        'titre' => 'Activer les notifications',
                                    )
                                );

                            ?>

                            <?php foreach ($controls as $control) : ?>
                                <div class="col-md-4 col-xl-3">
                                    <div class="card <?= $control['color'] ?> order-card">
                                        <a href="<?= HTTP.'control.php?action='.$control['action'] ?>" style="color: white; text-decoration: none">
                                            <div class="card-block">
                                                <h6 class="p-2"><?= $control['titre'] ?></h6>
                                                <?php /*<p class="m-b-0">Completed Orders<span class="f-right">351</span></p> */?>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Information</h1>
                </div>

                <!-- Content Row -->
                <div class="row">

                    <?php
                        $matos = getDataMatos();

                        foreach ($matos as $materiel) { ?>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"><?= $materiel['nom'] ?> (<?= $materiel['type'] ?>)</div>

                                                <?php if($materiel['status']['power_state'] != null) : ?>

                                                    <?php if($materiel['status']['power_state'] == true) : ?>
                                                            Branché, sous tension <i class="fas fa-circle" style="color: #169b6b"></i><br>
                                                        <?php else: ?>
                                                            Débranché, hors tension <i class="fas fa-circle" style="color: #d52a1a"></i><br>
                                                    <?php endif; ?>

                                                <?php endif; ?>



                                                <?php if($materiel['status']['shutter_state'] != null) : ?>

                                                    <?php if($materiel['status']['shutter_state'] == true) : ?>
                                                            Cache baissé <i class="fas fa-eye-slash"></i><br>
                                                        <?php else: ?>
                                                            Cache relevé <i class="fas fa-eye"></i><br>
                                                    <?php endif; ?>

                                                <?php endif; ?>



                                                <?php if($materiel['status']['batterie'] != null ) : ?>
                                                    <div class="o-progress-circle o-progress-circle--rounded">
                                                        <div class="o-progress-circle__fill">
                                                            <svg class="icon" viewBox="0 0 40 40">
                                                                <circle r="15.9154943092" cy="20" cx="20" />
                                                                <circle r="15.9154943092" cy="20" cx="20" style="<?= 'stroke:'.getColorBatterie($materiel['status']['batterie']) ?>" stroke-dashoffset="<?= abs($materiel['status']['batterie'] - 100) ?>" transform="rotate(-90,20,20)" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $materiel['status']['batterie'] ?> %</div>
                                                <?php endif; ?>

                                                <?php if($materiel['status']['temperature'] != null) : ?>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($materiel['status']['temperature'], '1', ',', ' ') ?> °C</div>
                                                <?php endif; ?>

                                                <?php if($materiel['status']['wifi_level_percent'] != null) : ?>
                                                    Signal Wifi <?= $materiel['status']['wifi_level_percent'].'%' ?> <i class="fas fa-wifi"></i><br>
                                                <?php endif; ?>

                                                <?php if($materiel['status']['rlink_quality_percent'] != null) : ?>
                                                    Signal vers le link <?= $materiel['status']['rlink_quality_percent'].'%' ?> <i class="fas fa-signal-stream"></i><br>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    ?>

                </div>

                <!-- Content Row -->

                <div class="row">

                    <div class="container">
                        <?php $i = 1; foreach (getDataCalendar() as $calendar) : ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex flex-column flex-lg-row">
                                        <div class="row flex-fill">
                                            <div class="col-sm-5">
                                                <h4 class="h5"> Agenda n° <?= $i ?></h4>
                                                <span class="badge bg-success" style="color: white"><?= $calendar['time'] ?></span>
                                                <span class="badge bg-gradient-dark" style="color: white"><?= translateSecurityLevel($calendar['action']) ?></span>
                                            </div>
                                            <div class="col-sm-4 py-2">
                                                <?php foreach ($calendar['jours'] as $jour) : ?>
                                                    <span class="badge bg-primary" style="color: white"><?= getTranslateDay($jour) ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="col-sm-3 text-lg-end">
                                                <?php if($calendar['actif'] == true) : ?>
                                                    <a href="?action=scenario&id=<?= $calendar['scenario_id'] ?>&enabled=false" class="btn btn-danger">Désactiver</a>
                                                <?php else: ?>
                                                    <a href="?action=scenario&id=<?= $calendar['scenario_id'] ?>&enabled=true" class="btn btn-success">Activer</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php $i++; endforeach; ?>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4"></div>

                    <div class="col-md-4">
                        <div id="map" style="height: 280px; max-width: 450px"></div>
                    </div>

                    <div class="col-md-4"></div>

                </div>

                <br>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; <a href="https://github.com/MiisterNarsikBis/Somfy_Home_ALARM_API">MiisterNarsik - Somfy Alarm</a> 2022 </span>
                    <span><a href="https://www.paypal.com/paypalme/miisternarsik">Paie moi un petit coca ?</a> </span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Bootstrap core JavaScript-->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"></script>

<script>
    $(document).ready(function (){

        let lat = "<?= getInfoAlarm()['lat'] ?>";
        let lng = "<?= getInfoAlarm()['lng'] ?>";

        var map = L.map('map').setView([lat, lng], 13);

        var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);

        var marker = L.marker([lat, lng]).addTo(map);

        let nom = "<?= getInfoAlarm()['nom'] ?>";
        marker.bindPopup("<h3>" + nom + "</h3>")



    })

</script>

</body>

</html>


