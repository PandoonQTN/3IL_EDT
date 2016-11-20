<?php
require_once("include/autoload.inc.php");
session_start();
require_once("include/header.inc.php");
require_once 'include/config.inc.php';
//Permet d'initialiser Phalcon
$app = new Phalcon\Mvc\Micro();
?>

<div id="corps">
    <?php
    include_once 'include/webserv.php';
    ?>
</div>
<div id="spacer"></div> 
<?php
//Permet de désitinitialiser Phalcon
$app->handle();



