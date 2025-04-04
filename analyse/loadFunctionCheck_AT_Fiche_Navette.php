<?php

// Fichier servant à lancer la fonction de vérification du nom de l'At dans la fiche navette depuis la requête ajax

include '../model/base.php';
include '../config/variables_globales.php';
@require '../analyse/check_AT_FICHE_NAVETTE.php';

// Execution de la fonction et récupération du retour dans $resultATFicheNavette
$resultATFicheNavette = checkATinFicheNavette($_POST['param1'], $_POST['param2'], $_POST['param3']);

// Récupération du contenu du fichier de log personnel (on utilise le chemin défini dans les variables globales)
$jsonData = file_get_contents(TEAMS_PATH_FILE_LOGS);

// Récupération du contenu du fichier JSON en tableau php
$data = json_decode($jsonData, true);

// Création de la ligne de résumé à ajouter
$resume = "AT sur Fiche navette : ". $resultATFicheNavette."<br>";

// Récupération de la dernière case du premier tableau, celui des jours où des recherches ont été éffectuées
$last_case = count($data)-1;

// On veut modifié la dernière case du dernier tableau pour lui ajouté le résumé de la fonction
// On est certain que cette case existe puisqu'avant de pouvoir exécuté cette fonction, pn a d'abord fait une recherche qui a été inscrite dans l'historique
// Cette fonction étant un supplément d'information, les possibles bugs ont été géré dans la page mère
$data[$last_case][array_key_first($data[$last_case])][array_key_last($data[$last_case][array_key_first($data[$last_case])])][array_key_last($data[$last_case][array_key_first($data[$last_case])][array_key_last($data[$last_case][array_key_first($data[$last_case])])])].= $resume;

// On transforme le tableau php en JSON
$jsonModifie = json_encode($data);

// On modifie le fichier de l'historique
file_put_contents(TEAMS_PATH_FILE_LOGS, $jsonModifie);

?>