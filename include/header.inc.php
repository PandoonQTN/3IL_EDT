<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <?php
        //Permet de ne pas charger la balise meta (qui permet de rendre la page responsive) 
        //pour la page fiche client, car l'écran étant séparé en 4, si on réduit encore alors 
        //l'image devient illisible. Le plus la taille des cadres est générée avec du JS
        if (isset($_SERVER['REDIRECT_URL'])) {
            if (($_SERVER['REDIRECT_URL']) == "/ficheclient" 
                    ||
                    ($_SERVER['REDIRECT_URL']) == "/detail/posteInfo" 
                    ||
                    ($_SERVER['REDIRECT_URL']) == "/detail/poste" 
                    ||
                    ($_SERVER['REDIRECT_URL']) == "/detail/serv" 
                    ||
                    ($_SERVER['REDIRECT_URL']) == "/detail/servInfo"
                    ||
                    ($_SERVER['REDIRECT_URL']) == "/detail/bdd" 
                    ||
                    ($_SERVER['REDIRECT_URL']) == "/detail/bddInfo" 
                    ||
                    ($_SERVER['REDIRECT_URL']) == "/detail/saam" 
                    ||
                    ($_SERVER['REDIRECT_URL']) == "/detail/saamInfo"
                    ||
                    ($_SERVER['REDIRECT_URL']) == "/getCatBase" 
                    ||
                    ($_SERVER['REDIRECT_URL']) == "/getCatBase" 
                    ||
                    ($_SERVER['REDIRECT_URL']) == "/getBase" 
                    ||
                    ($_SERVER['REDIRECT_URL']) == "/getLog"
                    ||
                    ($_SERVER['REDIRECT_URL']) == "/getCat") {
                
            } else {
                ?>
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <?php
            }
        }
        ?>
        <title>SCNiD</title>
        <link rel="stylesheet" type="text/css" href="css/stylesheet.css" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/tree.css" />
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

        <link rel="icon" type="image/png" href="img/logoScanidfavicon.png" />
        <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/script.js"></script>

    </head>
    <body onresize="taille()">