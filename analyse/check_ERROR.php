<?php

// FICHIER APPELE DANS : _base.php

// FONCTION UTILISEE DANS : _base.php

// DESCRIPTION : 

/*
    Cette fonction affiche un cadre contenant le code d'erreur trouvé dans le compte client et sa signification 
    Le cadre est de couleur verte pour un code d'erreur égale à 000, 509, 100 ou 055 et rouge pour tout autre code d'erreur
*/

// ARGUMENTS :
/*
    $data :
        type : array
        description : les données résultantes de l'execution de toutes les requêtes de la fonction executeRequests(...)

    $nameDatabase : 
        type : chaine de caractère
        description : contient le nom de la base de données utilisée pour la recherche   
*/

// RETOUR : chaîne de caractère

function rule_check_error(array $data, $nameDatabase){        
    
    // Initialisation du retour par défaut
    $codeError = 'Code erreur : pas de résultat';
    
    // Si des données ont été passées à la fonction :
    if(!empty($data)){     
        
        // Pour chaque objet de $data :
        foreach ($data as $item) {      
            
           

            // Regarder qu'on a un dossier client
            if($item[0][0]["OBJECT_TYPE_ID"] == "8050748698013937493"){

                // Initialisation du numéro de rang par défaut
                $value = -1;
                
                // Pour chaque objet de $item au rang 1 du tableau :
                for($i = 0; $i < count($item[1]); $i++){
                    // Si son champs 'NAME' correspond à 'Error Status'
                    if($item[1][$i]["NAME"] == "Error Status"){
                        // Donne à $value la valeur de $i correspondant à son rang dans le tableau
                        $value = $i;
                    }
                }

                // S'il y a un résultat pour la clé 'PARAMS_VALUE' au rang $value dans le tableau au rang 1 de $item :
                if($item[1][$value]["PARAMS_VALUE"]){
                    
                    // Initialiser le timer
                    $time_start = microtime(true);

                    // Si le résultat est égale au code d'erreur 000 ou 509 ou 100 ou 055 :
                    if(in_array($item[1][$value]["PARAMS_VALUE"], array('000', '509', '100', '055'))){
                        // Compléter l'affichage (= cadre vert)
                        $affichage = "<div class='o-rule__m-container o-rule__m-container--green'>
                        <h4 class='o-rule__a-title'>Code d'erreur - PAS DE CODE D'ERREUR</h4><nav class='o-rule__a-more'>";   
                        $codeError = 'Code erreur : pas de code erreur';                     
                    }
                    // Si le code d'erreur est différent :
                    else{
                        // Compléter l'affichage (= cadre rouge)
                        $affichage = "<div class='o-rule__m-container o-rule__m-container--red'>
                        <h4 class='o-rule__a-title'>Code d'erreur - ".$item[1][$value]["PARAMS_VALUE"]."</h4><nav class='o-rule__a-more'>";
                        $codeError = 'Code erreur : '.$item[1][$value]["PARAMS_VALUE"];
                    }

                    // Réinitialisation du numéro de rang par défaut
                    $value = -1;

                    // Pour chaque objet de $item au rang 1 du tableau :
                    for($j = 0; $j < count($item[1]); $j++){
                        // Si son champs 'NAME' correspond à 'Error label'
                        if($item[1][$j]["NAME"] == "Error label"){
                            // Donne à $value la valeur de $j correspondant à son rang dans le tableau
                            $value = $j;
                        }                    
                    }

                    // S'il y a un résultat pour la clé 'PARAMS_VALUE' au rang $value dans le tableau au rang 1 de $item :
                    if($item[1][$value]["PARAMS_VALUE"]){
                        // Compléter l'affichage avec l'intitulé de l'erreur
                        $affichage .= $item[1][$value]["PARAMS_VALUE"];
                    }

                    // Récupération du temps écoulé depuis le lancement du premier timer
                    $time_end = microtime(true);
                    $time = round(($time_end - $time_start) * 1000, 3);

                    // Compléter le code html
                    $affichage .= "</nav><div class='timer'>Temps de réponse : $time ms</div></div></div>";

                    // Afficher 
                    echo $affichage;

                    // Retourner le résultat
                    return $codeError;
                    
                }
                
            }
            else{                    
                $codeError = "Code erreur : pas de résultat car pas de compte client trouvé";
            }
            
        }
        // Retourner le résultat
        return $codeError;
    }
    else{
        return $codeError;
    }
}

?>