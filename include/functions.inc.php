<?php

/*
 * Permet d'afficher un message d'erreur. 
 */

function erreur($err = '') {
    $mess = ($err != '') ? $err : 'Une erreur inconnue s\'est produite';
    exit('<p>' . $mess . '</p>
   <p>Cliquez <a href="./">ici</a> pour revenir à la page d\'accueil</p>');
}

/*
 * Fonction permettant de supprimer le contenu d'un dossier. 
 * $Param 
 *      $folder : dossier que l'on veut vider
 */

function clearFolder($folder) {
    //ouvrir le dossier
    $dossier = opendir($folder);
    //Tant que le dossier n'est pas vide
    while ($fichier = readdir($dossier)) {
        //Sans compter . et ..
        if ($fichier != "." && $fichier != "..") {
            //On selectionne le fichier et on le supprime
            $Vidage = $folder . $fichier;
            unlink($Vidage);
        }
    }
    //Fermer le dossier vide
    closedir($dossier);
}

/*
 * Fonction permettant de connaitre le nombre de fichier dans un dossier. 
 * $Param 
 *      $dir : dossier dont on veut connaitre le nombre de fichier
 */

function count_files($dir) {
    $num = 0;
    $dir_handle = opendir($dir);
    while ($entry = readdir($dir_handle)) {
        if (is_file($dir . '/' . $entry)) {
            $num++;
        }
    }
    closedir($dir_handle);
    return $num;
}

/*
 * Fonction permettant de transformer une date Française au format Anglais
 */

function getEnglishDate($date) {
    $membres = explode('/', $date);
    $date = $membres[2] . '-' . $membres[1] . '-' . $membres[0];
    return $date;
}

/*
 * Fonction permettant de transformer une date Anglaise au format Française
 */

function getFrenchDate($date) {
    $membres = explode('-', $date);
    $date = $membres[2] . '/' . $membres[1] . '/' . $membres[0];
    return $date;
}

/*
 * Fonction permettant de transformer une date au format datetime en une date de type 
 *      JJ/MM/AAAA à HH:MM:SS
 */

function transformeDate($array) {
    if (!is_null($array)) {
        $datearray = explode(' ', $array);
        $datearray[0] = getFrenchDate($datearray[0]);
        $datearray[1] = explode('.', $datearray[1]);
        return $date = $datearray[0] . " à " . $datearray[1][0];
    } else {
        return $datearray = "---";
    }
}

/*
 * Fonction permettant d'afficher une barre de navigation pour permettre un affichage du résultat d'une recherche par page. 
 * @Param 
 *      $nb_total : nombre de résultat total que la recherche retourne
 *      $nb_affichage_par_page : nombre de ligne qui sera afficher par page
 *      $debut : numéro de la ligne de la première ligne de la page choisie 
 *      $nb_liens_dans_la_barre : nombre de liens ou l'utilisateur pourra cliquer
 */

function barre_navigation($nb_total, $nb_affichage_par_page, $debut, $nb_liens_dans_la_barre) {
    $barre = '';
    // on recherche l'URL courante munie de ses paramètre auxquels on ajoute le paramètre 'debut' qui jouera le role du premier élément de notre LIMIT
    if ($_SERVER['QUERY_STRING'] == "") {
        $query = $_SERVER['PHP_SELF'] . '?debut=';
    } else {
        $tableau = explode("debut=", $_SERVER['QUERY_STRING']);
        $nb_element = count($tableau);
        if ($nb_element == 1) {
            $query = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] . '&debut=';
        } else {
            if ($tableau[0] == "") {
                $query = $_SERVER['PHP_SELF'] . '?debut=';
            } else {
                $query = $_SERVER['PHP_SELF'] . '?' . $tableau[0] . 'debut=';
            }
        }
    }

    // on calcul le numéro de la page active
    $page_active = floor(($debut / $nb_affichage_par_page) + 1);
    // on calcul le nombre de pages total que va prendre notre affichage
    $nb_pages_total = ceil($nb_total / $nb_affichage_par_page);

    // on calcul le premier numero de la barre qui va s'afficher, ainsi que le dernier ($cpt_deb et $cpt_fin)
    // exemple : 2 3 4 5 6 7 8 9 10 11 << $cpt_deb = 2 et $cpt_fin = 11
    if ($nb_liens_dans_la_barre % 2 == 0) {
        $cpt_deb1 = $page_active - ($nb_liens_dans_la_barre / 2) + 1;
        $cpt_fin1 = $page_active + ($nb_liens_dans_la_barre / 2);
    } else {
        $cpt_deb1 = $page_active - floor(($nb_liens_dans_la_barre / 2));
        $cpt_fin1 = $page_active + floor(($nb_liens_dans_la_barre / 2));
    }

    if ($cpt_deb1 <= 1) {
        $cpt_deb = 1;
        $cpt_fin = $nb_liens_dans_la_barre;
    } elseif ($cpt_deb1 > 1 && $cpt_fin1 < $nb_pages_total) {
        $cpt_deb = $cpt_deb1;
        $cpt_fin = $cpt_fin1;
    } else {
        $cpt_deb = ($nb_pages_total - $nb_liens_dans_la_barre) + 1;
        $cpt_fin = $nb_pages_total;
    }

    if ($nb_pages_total <= $nb_liens_dans_la_barre) {
        $cpt_deb = 1;
        $cpt_fin = $nb_pages_total;
    }
    // si le premier numéro qui s'affiche est différent de 1, on affiche << qui sera un lien vers la premiere page
    if ($cpt_deb != 1) {
        $cible = $query . (0);
        $lien = '<A onclick="charger(\'' . $cible . '\')">&lt;&lt;</A>&nbsp;&nbsp;';
    } else {
        $lien = '';
    }
    $barre .= $lien;
    // on affiche tous les liens de notre barre, tout en vérifiant de ne pas mettre de lien pour la page active
    for ($cpt = $cpt_deb; $cpt <= $cpt_fin; $cpt++) {
        if ($cpt == $page_active) {
            if ($cpt == $nb_pages_total) {
                $barre .= $cpt;
            } else {
                $barre .= $cpt . '&nbsp;-&nbsp;';
            }
        } else {
            if ($cpt == $cpt_fin) {
                $barre .= "<A onclick=\"charger('" . $query . (($cpt - 1) * $nb_affichage_par_page);
                $barre .= "')\">" . $cpt . "</A>";
            } else {

                $barre .= "<A onclick=\"charger('" . $query . (($cpt - 1) * $nb_affichage_par_page);
                $barre .= "')\">" . $cpt . "</A>&nbsp;-&nbsp;";
            }
        }
    }
    $fin = ($nb_total - ($nb_total % $nb_affichage_par_page));
    if (($nb_total % $nb_affichage_par_page) == 0) {
        $fin = $fin - $nb_affichage_par_page;
    }
    // si $cpt_fin ne vaut pas la dernière page de la barre de navigation, on affiche un >> qui sera un lien vers la dernière page de navigation
    if ($cpt_fin != $nb_pages_total) {
        $cible = $query . $fin;
        $lien = '&nbsp;&nbsp;<A onclick="charger(\'' . $cible . '\')">&gt;&gt;</A>';
    } else {
        $lien = '';
    }
    $barre .= $lien;
    return $barre;
}

/*
 * Fonction permettant de générer un graphique sous forme de "camembert" 
 * @Param
 *      $tabNom : tableau contenant les données dont on veut avoir le pourcentage
 *      $tabVal : valeur correspondant aux données
 *      $titre : titre du graphique
 */

function genererCamembert($tabNom, $tabVal, $titre) {
    /* Les valeurs */
    $MyData = new pData();
    $MyData->addPoints($tabVal, "ScoreA");
    $MyData->setSerieDescription("ScoreA", "Application A");

    /* Définir la catégorie */
    $MyData->addPoints($tabNom, "Labels");
    $MyData->setAbscissa("Labels");

    /* Créer un objet pChart */
    $myPicture = new pImage(600, 520, $MyData);

    /* Dessiner le fond */
    $myPicture->drawRectangle(0, 0, 600, 520);

    /* Dessiner la barre noir */
    $Settings = array("StartR" => 255, "StartG" => 255, "StartB" => 255, "EndR" => 255, "EndG" => 255, "EndB" => 255, "Alpha" => 50);
    $myPicture->drawGradientArea(0, 0, 600, 520, DIRECTION_VERTICAL, $Settings);
    $myPicture->drawGradientArea(0, 0, 600, 40, DIRECTION_VERTICAL, array("StartR" => 0, "StartG" => 0, "StartB" => 0, "EndR" => 50, "EndG" => 50, "EndB" => 50, "Alpha" => 100));

    /* Ajout de la bordure  */
    $myPicture->drawRectangle(0, 0, 599, 519, array("R" => 0, "G" => 0, "B" => 0));

    /* Ecrire un titre */
    $myPicture->setFontProperties(array("FontName" => "fonts/MankSans.ttf", "FontSize" => 18));
    $myPicture->drawText(25, 30, $titre, array("R" => 255, "G" => 255, "B" => 255));

    /* Police par défaut */
    $myPicture->setFontProperties(array("FontName" => "fonts/Forgotte.ttf", "FontSize" => 16, "R" => 80, "G" => 80, "B" => 80));

    /* Met une ombre derière de cercle */
    $myPicture->setShadow(TRUE, array("X" => 2, "Y" => 2, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 50));

    /* Créer le diagramme */
    $PieChart = new pPie($myPicture, $MyData);

    /* Dessine le diagramme */
    $PieChart->draw2DRing(400, 280, array("WriteValues" => TRUE, "ValueR" => 0, "ValueG" => 0, "ValueB" => 0, "Border" => TRUE));

    /* Dessine la légende */
    $myPicture->setShadow(FALSE);
    $PieChart->drawPieLegend(30, 80, array("Alpha" => 40, "FontSize" => 16));

    /* J'indique le chemin où je souhaite que mon image soit créée */
    $myPicture->Render("img/graph/" . $titre . ".png");
}

/*
 * Fonction permettant de générer un graphique  
 * @Param
 *      $tabNom : tableau contenant les données en abscisse
 *      $tabVal : valeur correspondant aux données en ordonnées
 *      $titre : titre du graphique
 */

function genererGraphique2($tabNom, $tabVal, $titre) {
    /* Je présente ma série de données à utiliser pour le graphique et je détermine le titre de l'axe vertical avec setAxisName */
    $MyData = new pData();
    $MyData->addPoints($tabVal, "Probe 3");
    //Pour l'épaisseur du trait
    $MyData->setSerieWeight("Probe 3", 2);
    $MyData->setAxisName(0, "Nombre de Poste");

    /* J'indique les données horizontales du graphique. Il doit y avoir le même nombre que pour ma série de données précédentes (logique) */
    $MyData->addPoints($tabNom, "Labels");
    $MyData->setSerieDescription("Labels", "Nom Client");
    $MyData->setAbscissa("Labels");
    $MyData->setPalette("Probe 3", array("R" => 255, "G" => 0, "B" => 0));

    /* Je crée l'image qui contiendra mon graphique précédemment crée */
    $myPicture = new pImage(900, 330, $MyData);

    /* Je crée une bordure à mon image */
    $myPicture->drawRectangle(0, 0, 899, 329, array("R" => 0, "G" => 0, "B" => 0));

    /* J'indique le titre de mon graphique, son positionnement sur l'image et sa police */
    $myPicture->setFontProperties(array("FontName" => "fonts/Forgotte.ttf", "FontSize" => 11));
    $myPicture->drawText(200, 25, $titre, array("FontSize" => 20, "Align" => TEXT_ALIGN_BOTTOMMIDDLE));

    /* Je choisi le fond de mon graphique */
    $myPicture->setFontProperties(array("FontName" => "fonts/pf_arma_five.ttf", "FontSize" => 6));

    /* Je détermine la taille du graphique et son emplacement dans l'image */
    $myPicture->setGraphArea(60, 40, 800, 310);

    /* Paramètres pour dessiner le graphique à partir des deux abscisses */
    $scaleSettings = array("XMargin" => 10, "YMargin" => 10, "Floating" => TRUE, "GridR" => 200, "GridG" => 200, "GridB" => 200, "DrawSubTicks" => TRUE, "CycleBackground" => TRUE);
    $myPicture->drawScale($scaleSettings);

    /* J'insère sur le côté droit le nom de l'auteur et les droits 
      $myPicture->setFontProperties(array("FontName"=>"fonts/Bedizen.ttf","FontSize"=>6));
      $TextSettings = array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Angle"=>90,"FontSize"=>10);
      $myPicture->drawText(860,300,"Création : ",$TextSettings); */

    /* Je dessine mon graphique en fonction des paramètres précédents */
    $myPicture->drawAreaChart();
    $myPicture->drawLineChart();

    /* Je rajoute des points rouge (plots) en affichant par dessus les données */
    $myPicture->drawPlotChart(array("DisplayValues" => TRUE, "PlotBorder" => TRUE, "BorderSize" => 2, "Surrounding" => -60, "BorderAlpha" => 80));

    $myPicture->Render("img/graph/" . $titre . ".png");
}

/*
 * Fonction permettant de générer un graphique  
 * @Param
 *      $tabNom : tableau contenant les données en abscisse
 *      $tabVal : valeur correspondant aux données en ordonnées
 *      $titre : titre du graphique
 */

function genererGraphique($tabNom, $tabVal, $titre) {
    /* Create and populate the pData object */
    $MyData = new pData();
    $MyData->addPoints($tabVal, "Valeur");
    $MyData->setAxisName(0, "Valeur");
    $MyData->addPoints($tabNom, "Nom");
    $MyData->setSerieDescription("Nom", "Nom");
    $MyData->setAbscissa("Nom");

    /* Create the pChart object */
    $myPicture = new pImage(500, 500, $MyData);
    $myPicture->setFontProperties(array("FontName" => "fonts/pf_arma_five.ttf", "FontSize" => 6));

    /* Draw the chart scale */
    $myPicture->setGraphArea(100, 60, 480, 480);
    $myPicture->drawScale(array("CycleBackground" => TRUE, "DrawSubTicks" => TRUE, "Pos" => SCALE_POS_TOPBOTTOM));
   
    /* J'indique le titre de mon graphique, son positionnement sur l'image et sa police */
    $myPicture->setFontProperties(array("FontName" => "fonts/Forgotte.ttf", "FontSize" => 11));
    $myPicture->drawText(200, 25, $titre, array("FontSize" => 20, "Align" => TEXT_ALIGN_BOTTOMMIDDLE));

    
    /* Turn on shadow computing */
    $myPicture->setShadow(TRUE, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));

    /* Draw the chart */
    $myPicture->drawBarChart(array("DisplayPos" => LABEL_POS_INSIDE, "DisplayValues" => TRUE, "Rounded" => TRUE, "Surrounding" => 30));

    /* Write the legend */
    $myPicture->drawLegend(570, 215, array("Style" => LEGEND_NOBORDER, "Mode" => LEGEND_HORIZONTAL));

    /* Render the picture (choose the best way) */
    $myPicture->Render("img/graph/" . $titre . ".png");
}
