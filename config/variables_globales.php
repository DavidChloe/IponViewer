<?php

/*

Ce fichier est au coeur d'Ipon Viewer.
Ici sont régroupées tous les paramètres pour la connexion à chaque base de données ainsi que les chemin définis vers les emplacements des dossiers et fichiers de Teams utilisés par le code.

*/

//define("TEAMS_PATH_LOGS","C:/Users/".get_current_user()."/orange.com/Echange_DomaineRéseau - Ecosystème_Conception/IPON Viewer/Suivi des utilisateurs/logs");

define("TEAMS_PATH_LOGS","C:\My Program Files\XAMPP\htdocs\ipon viewer\logs"); //en local

define("TEAMS_PATH_FILE_LOGS","C:\My Program Files\XAMPP\htdocs\ipon viewer\logs\user_logs.json");

define("TEAMS_PATH_ANNUAIRE","C:/Users/".get_current_user()."/orange.com/Echange_DomaineRéseau - Ecosystème_Conception/IPON Viewer/Suivi des utilisateurs/annuaire_utilisateurs.txt");

define("TEAMS_PATH_SUIVI_INSTALLATION","C:/Users/".get_current_user()."/orange.com/Echange_DomaineRéseau - Ecosystème_Conception/IPON Viewer/Suivi des utilisateurs/suivi_des_installations.txt");

define("TEAMS_PATH_DOCUMENTATION","C:/Users/".get_current_user()."/orange.com/Echange_DomaineRéseau - Ecosystème_Conception/IPON Viewer/Documentation");

define("TEAMS_PATH_REPOSITORY","C:/Users/".get_current_user()."/orange.com/Echange_DomaineRéseau - Ecosystème_Conception/IPON Viewer");

define("REPERTOIRE_IPON_VIEWER", dirname(__DIR__));



// Paramètres bdd d'IPON
define("SID_IPON", "PIPODB1");
define("HOST_IPON", "opipodbg4-adm.rouen.francetelecom.fr");
define("PORT_IPON", "20340");
define("USER_IPON", "netcracker1");
define("PWD_IPON", "Cker]Ipo12");


// Paramètres bdd d'IPON RIP
define("SID_IPON_RIP", "PIPODB2");
define("HOST_IPON_RIP", "opipodbr4-adm.rouen.francetelecom.fr");
define("PORT_IPON_RIP", "20340");
define("USER_IPON_RIP", "netcracker1");
define("PWD_IPON_RIP", "Cker]Ipo12");


// Paramètres bdd GEORESO RIP
define("HOST_GEORESO", "ger-db1.reseau.francetelecom.fr");
define("DB_NAME_GEORESO", "pgercm");
define("USER_GEORESO", "pgercmselect");
define("PWD_GEORESO", "Ger:1cms");

// Paramètres bdd GEORESO RIP
define("HOST_GEORESO_RIP", "ger-db1.reseau.francetelecom.fr");
define("DB_NAME_GEORESO", "pgercm");
define("USER_GEORESO", "pgercmselect");
define("PWD_GEORESO", "Ger:1cms");
?>