<script>
    $(document).ready(function () {

    });


</script>

<?php

function v($var) {
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

function getCorrectDate($date) {
    $membres = explode('/', $date);
    $date = $membres[2] . $membres[1] . $membres[0];
    return $date;
}

function definirHeure($num) {
    switch ($num) {
        case 1:
            $heures = array(
                'debut' => "083000",
                'fin' => "100000"
            );
            break;
        case 2:
            $heures = array(
                'debut' => "103000",
                'fin' => "120000"
            );
            break;
        case 3:
            $heures = array(
                'debut' => "120000",
                'fin' => "133000"
            );
            break;
        case 4:
            $heures = array(
                'debut' => "133000",
                'fin' => "150000"
            );
            break;
        case 5:
            $heures = array(
                'debut' => "151500",
                'fin' => "164500"
            );
            break;
        case 6:
            $heures = array(
                'debut' => "170000",
                'fin' => "183000"
            );
            break;

        default :
            break;
    }
    return $heures;
}

if (!file_exists('./XML/xml.xml')) {
    exit('Echec lors de l\'ouverture du fichier .xml');
}

$xml = simplexml_load_file('./XML/xml.xml');
//print_r($xml);
//Pour compter le nombre de fois on l'on va passer dans le foreach
$nb = count($xml);


$xmltostring = json_decode(json_encode($xml), TRUE);
//v($xmltostring);
$nb = count($xmltostring);


// Permet de transformer l'objet simpleXML en string pour pouvoir ajouter les données en base. 
$xmltostring = json_decode(json_encode($xmltostring), TRUE);


// Création du début du fichier calendar
$monics = "BEGIN:VCALENDAR" . chr(10);
$monics = $monics . "VERSION:2.0" . chr(10);
$monics = $monics . "PRODID:-//hacksw/handcal//NONSGML v1.0//EN" . chr(10);


// Début du parcours du fichier XML
$GROUPE = $xmltostring['GROUPE'];
$PLAGE = $GROUPE['PLAGES'];
$SEMAINES = $PLAGE['SEMAINE'];



foreach ($SEMAINES as $sem) {
    if (is_array($sem)) {
        foreach ($sem as $jour) {
            if (is_array($jour)) {
                foreach ($jour as $j) {
                    foreach ($j["CRENEAU"] as $creneau) {
                        if (isset($creneau['Activite'])) {
                            $monics = $monics . "BEGIN:VEVENT" . chr(10);
                            $heure = definirHeure($creneau['Creneau']);
                            $monics = $monics . "DTSTART:" . getCorrectDate($j['Date']) . "T" . $heure['debut'] . chr(10);
                            $monics = $monics . "DTEND:" . getCorrectDate($j['Date']) . "T" . $heure['fin'] . chr(10);
                            $monics = $monics . "SUMMARY:" . $creneau['Activite'] . chr(10);
                            if (isset($creneau['Salles']) && !is_array($creneau['Salles'])) {
                                $monics = $monics . "LOCATION:" . $creneau['Salles'] . chr(10);
                            }
                            $monics = $monics . "DESCRIPTION:" . chr(10);
                            $monics = $monics . "END:VEVENT" . chr(10);
                        }
                    }
                }
            }
        }
    } else {
        v("Erreur dans la lecture du formulaire.. La semaine n'est pas un tableau");
    }
}
// Fin du fichier 
$monics = $monics . "END:VCALENDAR" . chr(10);


// écrire dans fichier
$ficin = "./calendrier.ics";
$monfic = fopen($ficin, "w");
fwrite($monfic, $monics);
fclose($monfic);
echo "Télécharger au format <a href=\"./calendrier.ics\">iCalendar</a>";

/*
//----------------------------------
// Construction de l'entête
//----------------------------------
// On choisi généralement de construire une frontière générée aléatoirement
// comme suit. (le document pourra ainsi etre attache dans un autre mail
// dans le cas d'un transfert par exemple)
$boundary = "-----=".md5(uniqid(rand()));

// Ici, on construit un entête contenant les informations
// minimales requises.
// Version du format MIME utilisé
$header = "MIME-Version: 1.0\r\n";
// Type de contenu. Ici plusieurs parties de type different "multipart/mixed"
// Avec un frontière définie par $boundary
$header .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
$header .= "\r\n";

//--------------------------------------------------
// Construction du message proprement dit
//--------------------------------------------------

// Pour le cas, où le logiciel de mail du destinataire
// n'est pas capable de lire le format MIME de cette version
// Il est de bon ton de l'en informer
// REM: Ce message n'apparaît pas pour les logiciels sachant lire ce format
$msg = "Je vous informe que ceci est un message au format MIME 1.0 multipart/mixed.\r\n";

//---------------------------------
// 1ère partie du message
// Le texte
//---------------------------------
// Chaque partie du message est séparée par une frontière
$msg .= "--$boundary\r\n";

// Et pour chaque partie on en indique le type
$msg .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
// Et comment il sera codé
$msg .= "Content-Transfer-Encoding:8bit\r\n";
// Il est indispensable d'introduire une ligne vide entre l'entête et le texte
$msg .= "\r\n";
// Enfin, on peut écrire le texte de la 1ère partie
$msg .= "Ceci est un mail avec un fichier joint\r\n";
$msg .= "\r\n";

//---------------------------------
// 2nde partie du message
// Le fichier
//---------------------------------
// Tout d'abord lire le contenu du fichier
$file = "./calendrier.ics";
$fp = fopen($file, "rb");   // b c'est pour les windowsiens
$attachment = fread($fp, filesize($file));
fclose($fp);

// puis convertir le contenu du fichier en une chaîne de caractères
// certe totalement illisible mais sans caractères exotiques
// et avec des retours à la ligne tout les 76 caractères
// pour être conforme au format RFC 2045
$attachment = chunk_split(base64_encode($attachment));

// Ne pas oublier que chaque partie du message est séparée par une frontière
$msg .= "--$boundary\r\n";
// Et pour chaque partie on en indique le type
$msg .= "Content-Type: text/calendar; name=\"$file\"\r\n";
// Et comment il sera codé
$msg .= "Content-Transfer-Encoding: base64\r\n";
// Petit plus pour les fichiers joints
// Il est possible de demander à ce que le fichier
// soit si possible affiché dans le corps du mail
$msg .= "Content-Disposition: attachment; filename=\"$file\"\r\n";
// Il est indispensable d'introduire une ligne vide entre l'entête et le texte
$msg .= "\r\n";
// C'est ici que l'on insère le code du fichier lu
$msg .= $attachment . "\r\n";
$msg .= "\r\n\r\n";

// voilà, on indique la fin par une nouvelle frontière
$msg .= "--$boundary--\r\n";

$destinataire = "sasuke8710@hotmail.fr";
$expediteur   = "quentin.doucet@outlook.fr";
$reponse      = $expediteur;
echo "Ce script envoie un mail avec fichier attaché à $expediteur";
mail($destinataire, "test avec fichier attaché", $msg,
     "Reply-to: $reponse\r\nFrom: $expediteur\r\n".$header);
?>*/

