<?php

// Fonction permettant de récupérer les nuémros de dossier et de compte client
// La fonction renvoie un tableau de 2 items maximum
function rule_get_links_client(array $data, $nameDatabase){

    // Initialiser le tableau des résultats
    $result = [];

    // Si des données on été passées à la fonction :
    if(!empty($data)){
        
        // Initialiser le timer
        $time_start = microtime(true);

        // Initialisation de l'affichage
        $affichage = "<div class='o-rule__m-container o-rule__m-container--gray'>";
                    
        // Pour chaque objet de $data
        foreach ($data as $item) {

            // On regarde si on peut récupérer l'object_id 
            if ($item[0][0]["OBJECT_ID"]) {
                
                // Compléter l'affichage
                $affichage .= "<div class='rule_container'><h4 class='o-rule__a-title'>";
        
                // Si l'object_type_id correspond au numéro identifiant un dossier :
                if ($item[0][0]["OBJECT_TYPE_ID"] == "8050748698013937493") {

                    // Compléter l'affichage
                    $affichage .= "<br>Numéro du dossier client";

                    // Ajouter l'information au tableau
                    $result[] = "1 dossier client";

                // Si l'object_type_id correspond au numéro identifiant un compte :
                } elseif ($item[0][0]["OBJECT_TYPE_ID"] == "2091353054013993289") {

                    // Compléter l'affichage
                    $affichage .= "Numéro de compte client";

                    // Ajouter l'information au tableau
                    $result[] = "1 compte client";

                // Sinon :
                } else {

                    // Compléter l'affichage
                    $affichage .= "Numéro de compte/dossier client";

                    // Ajouter l'information au tableau
                    $result[] = "Aucune correspondance avec un numéro de compte et de dossier client";
                }
        
                // Compléter l'affichage
                $affichage .= "</h4><nav class='o-rule__a-more'>";

                // En fonction du nom de la base de données sur laquelle les recheches sont effectuées on complète l'affichage avec le lien (ou pas) vers le logiciel IPON correspondant au compte ou au dossier
                if ($nameDatabase == 'IPON') {
                    $affichage .= "<a href='https://ipon-ihm.sso.francetelecom.fr/common/uobject.jsp?object=".$item[0][0]["OBJECT_ID"]."' target='_blank'>{$item[0][0]["OBJECT_ID"]}</a>";
                } elseif ($nameDatabase == 'RIP') {
                    $affichage .= "<a href='https://iponrip-ihm.sso.francetelecom.fr/common/uobject.jsp?object=".$item[0][0]["OBJECT_ID"]."' target='_blank'>{$item[0][0]["OBJECT_ID"]}</a>";
                } else {
                    $affichage .= "Auncun renseignement";
                } 
                
                // Compléter l'affichage
                $affichage .= "</nav></div>";
            }    
        }
        
        // Récupération du temps écoulé depuis le lancement du premier timer
        $time_end = microtime(true);
        $time = round(($time_end - $time_start) * 1000, 3);

        
        // Compléter l'affichage
        $affichage .= "<div class='timer'>Temps de réponse : $time ms</div></div>";
        
        // Afficher le cadre et son contenu
        echo $affichage;

        // Retourner le résultat
        return $result;
    }
    // Si aucune donnée n'a été passée à la focntion
    else{

        // Compléter le résultat
        $result[] = "Compte/Dossier client : pas de résultat";

        // Retourner le résultat
        return $result;
    }
    
}

?>