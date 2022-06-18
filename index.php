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

    </style>
    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

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

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Search -->

                    <div class="input-group ml-md-3 col-3">
                        <div class="sidebar-brand-icon rotate-n-15">
                            <i class="fas fa-laugh-wink"></i>
                        </div>
                        <div class="sidebar-brand-text mx-3">Somfy &nbsp;<sup>API</sup></div>
                    </div>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Batterie</h1>
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
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
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

                <!-- Content Row -->
                <div class="row">

                    <!-- Content Column -->
                    <div class="col-lg-6 mb-4">

                        <!-- Project Card Example -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
                            </div>
                            <div class="card-body">
                                <h4 class="small font-weight-bold">Server Migration <span
                                        class="float-right">20%</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
                                         aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <h4 class="small font-weight-bold">Sales Tracking <span
                                        class="float-right">40%</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"
                                         aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <h4 class="small font-weight-bold">Customer Database <span
                                        class="float-right">60%</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar" role="progressbar" style="width: 60%"
                                         aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <h4 class="small font-weight-bold">Payout Details <span
                                        class="float-right">80%</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
                                         aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <h4 class="small font-weight-bold">Account Setup <span
                                        class="float-right">Complete!</span></h4>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                         aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Color System -->
                        <div class="row">
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-primary text-white shadow">
                                    <div class="card-body">
                                        Primary
                                        <div class="text-white-50 small">#4e73df</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-success text-white shadow">
                                    <div class="card-body">
                                        Success
                                        <div class="text-white-50 small">#1cc88a</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-info text-white shadow">
                                    <div class="card-body">
                                        Info
                                        <div class="text-white-50 small">#36b9cc</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-warning text-white shadow">
                                    <div class="card-body">
                                        Warning
                                        <div class="text-white-50 small">#f6c23e</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-danger text-white shadow">
                                    <div class="card-body">
                                        Danger
                                        <div class="text-white-50 small">#e74a3b</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-secondary text-white shadow">
                                    <div class="card-body">
                                        Secondary
                                        <div class="text-white-50 small">#858796</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-light text-black shadow">
                                    <div class="card-body">
                                        Light
                                        <div class="text-black-50 small">#f8f9fc</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-dark text-white shadow">
                                    <div class="card-body">
                                        Dark
                                        <div class="text-white-50 small">#5a5c69</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-6 mb-4">

                        <!-- Illustrations -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                         src="img/undraw_posting_photo.svg" alt="...">
                                </div>
                                <p>Add some quality, svg illustrations to your project courtesy of <a
                                        target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a
                                    constantly updated collection of beautiful svg images that you can use
                                    completely free and without attribution!</p>
                                <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                                    unDraw &rarr;</a>
                            </div>
                        </div>

                        <!-- Approach -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
                            </div>
                            <div class="card-body">
                                <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce
                                    CSS bloat and poor page performance. Custom CSS classes are used to create
                                    custom components and custom utility classes.</p>
                                <p class="mb-0">Before working with this theme, you should become familiar with the
                                    Bootstrap framework, especially the utility classes.</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website 2021</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="login.html">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>


</body>

</html>


