<?php
//Connexion à la base SCANID
define('DBHOST', "SCANID");
define('DBPORT', "5432");
define('DBNAME', "scanid");
define('DBUSER', "postgres");
define('DBPASSWD', "N0emie");
//Connexion à la base de production pour la connexion
define('DBHOSTPROD', "PsqlMust");
define('DBPORTPROD', "5432");
define('DBNAMEPROD', "mustV5");
define('DBNAMEPRODINTRA', "INTRANET_DEV");
define('DBUSERPROD', "postgres");
define('DBPASSWDPROD', "N0emie");

define('ENV', 'dev');
?>