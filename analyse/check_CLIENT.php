<?php

// FICHIER APPELE DANS : 

// FONCTION UTILISEE DANS : 

// DESCRIPTION : 

/*  

*/

// ARGUMENTS :

/*  

*/

// RETOUR : 

/*  

*/

function rule_check_client ($id, array $data, $bdd) {
    $listIds = [];
    if ($data[2][0]["OBJECT_NAME"]) {
        $requestLignes = "SELECT OBJECT_ID FROM NC_OBJECTS WHERE NAME='{$data[2][0]["OBJECT_NAME"]}'";
        $prepareRequestLignes = $bdd->prepare($requestLignes);
        $prepareRequestLignes->execute();
        $resultsLignes = $prepareRequestLignes->fetchAll(PDO::FETCH_ASSOC);
        //var_dump($resultsLignes);
        if (count($resultsLignes) > 1) {
            foreach ($resultsLignes as $value) {
                $listIds[] = $value["OBJECT_ID"];
            }
        }
    }
    $string = "";
    if (count($listIds) > 0) {
        $string .= "<div class='o-rule__m-container o-rule__m-container--red'>
        <h4 class='o-rule__a-title'>Vérification Nom - ERREUR</h4>
        <nav class='o-rule__a-more '>
        Liste des ID de tous les Noms en doublons :
        <ul>";
        foreach ($listIds as $value) {
            $string .= "<li>".$value."</li>";
        }
        $string .= "</ul></nav></div>";
    }
    else {
        $string .= "<div class='o-rule__m-container o-rule__m-container--green'>
                    <h4 class='o-rule__a-title'>Vérification Nom - CONFORME</h4>
                    <nav class='o-rule__a-more'>Nom du client unique en base de données</nav>
                    </div>";
    }
    echo $string;
}

?>