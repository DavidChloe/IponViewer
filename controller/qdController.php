<?php
// IMPORTS
include '../config/database_functions.php';
include '../model/formateFunctionsQD.php';
include '../model/formateFunctions.php';
include '../script/saveInHistory.php';

/*

    Fichier de routage pour l'exécution des scripts de la gestion QD.
    Lors du clic sur l'une ou l'autre des cartes de la gestion QD, le name de l'input submit sert à rediriger vers le bon script php 

    Pour l'ajout d'une nouvelle règle : ajouter à la suite une nouvelle condition avec $_POST['nouveau_name_input_submit'] puis inclure le fichier contenant le script à éxécuter

*/


if(isset($_POST['post_tag_scanQD'])){

    include '../analyse/qd/qd.php';
    
}
if(isset($_POST['post_tag_scan_nom_immeuble'])){
    
    include '../analyse/qd/qdNomImmeuble.php';
}

if(isset($_POST['post_tag_scan_route_ptf'])){
    
    include '../analyse/qd/qdRoutePTF.php';
}





?>