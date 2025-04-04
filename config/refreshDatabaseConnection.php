<?php

// Initialisation à null
$recv = null;
// Reception du paramètre envoyé par la requête Ajax (= nom de la base de données)
$recv = $_POST['param1'];

// Inclusion des fichiers php nécessaires
include '../config/database_functions.php';
include '../config/variables_globales.php';
include '../model/base.php';



// Tableau des noms de base de données 
// Ces noms correspondent à ceux établis comme clefs dans database_functions.php pour la variable privée $dbConfigs (= un tableau) de la classe DatabaseConnectionPool
$databaseNames = [
    'Ipon',
    'IponRip',
    'Georeso',
    'GeoresoRip'
];

// Pour chacune de ces bases de données
foreach ($databaseNames as $dbName) {

    
    // Si le paramètre envoyé par la requête Ajax correspond à l'un des nom de base de données du tableau $databaseNames
    if ($recv == $dbName) {

        // Initialisation des variables
        $connexion = null;
        $jsonData = null;
        
        // Création de l'objet PDO servant à vérifier qu'une connexion à cette base de données est possible
        $connexion = DatabaseConnectionPool::createConnection($dbName);
        DatabaseConnectionPool::closeConnection($dbName);
        
        // Récupération des valeurs de retour
        $tabData = [];

        // Sauvegarde dans un tableau des valeurs retournées et identifiées par des clefs reprenant le nom de la base de données

        // Rang 1 : valeur true ou flase
        // true pour une connexion établie et false pour le contraire 
        $tabData['connexion' . $dbName] = $connexion[0];
        // Rang 2 : l'heure du test de connexion
        // au format hh:mm:ss
        $tabData['heureTest' . $dbName] = $connexion[1];
        // Rang 3 : le temps utilisé pour créer l'objet PDO et tester la connexion
        // en millisecondes
        $tabData['delaisConnexion' . $dbName] = $connexion[2];
        
        // Formatage des données pour utilisation dans la partie JS
        $jsonData = json_encode($tabData);

        // Utilisation du echo pour envoyer les données php à la requête ajax pour le traitement JS
        echo $jsonData;

    }
}

?>