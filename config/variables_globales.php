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
define("SID_IPON", "");
define("HOST_IPON", "");
define("PORT_IPON", "");
define("USER_IPON", "");
define("PWD_IPON", "");


// Paramètres bdd d'IPON RIP
define("SID_IPON_RIP", "");
define("HOST_IPON_RIP", "");
define("PORT_IPON_RIP", "");
define("USER_IPON_RIP", "");
define("PWD_IPON_RIP", "");


// Paramètres bdd GEORESO RIP
define("HOST_GEORESO", "");
define("DB_NAME_GEORESO", "");
define("USER_GEORESO", "");
define("PWD_GEORESO", "");

// Paramètres bdd GEORESO RIP
define("HOST_GEORESO_RIP", "");
define("DB_NAME_GEORESO", "");
define("USER_GEORESO", "");
define("PWD_GEORESO", "");
?>