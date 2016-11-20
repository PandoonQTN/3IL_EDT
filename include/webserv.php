<?php
//Page qui n'existe pas 
$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();

    ?>
    <h2 class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xs-push-1 col-sm-push-1 col-md-push-1 col-lg-push-1">Cette page n'existe pas :'( <br>
    </h2>

    <?php
});

//Page de base, connexion
$app->get('/', function () {
    if (!empty($_SESSION)) {
        if (isset($_SESSION['id'])) {
            $page = null;
            require_once 'include/Retransmission/index.inc.php';
            ?>    
            <h2 class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xs-push-1 col-sm-push-1 col-md-push-1 col-lg-push-1">Vous êtes à l'accueil.
                <small> Cette application vous permet de consulter les informations d'installations client ayant installé une version de MUST G5.</small></h2>
            <img src="img/logoScanid.png" alt="Logo Scanid" class="img img-responsive col-sm-4 col-md-4 col-lg-4 col-sm-push-4 col-md-push-4 col-lg-push-4">
            <?php
        }
    } else {
        include_once 'include/menu.inc.php';
        include_once 'include/functions.inc.php';
        include_once 'include/constants.inc.php';
        $page = null;
        require_once 'include/connexion.inc.php';
    }
});

//Appel réalisé après une recherche pour supprimer les variables de session
$app->get('/eraseSession', function() {
    $page = null;
    require_once 'include/Retransmission/eraseSession.php';
});
//Pour le test du SAAM en local via un XML 
$app->get('/postSAAM', function() {
    $page = null;
    require_once 'include/Retransmission/index.inc.php';
    require_once 'include/Tri/getEDT.php';
});


?> 