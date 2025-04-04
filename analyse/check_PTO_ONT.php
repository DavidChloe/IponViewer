<?php

// FICHIER APPELE DANS : _base.php

// FONCTION UTILISEE DANS : _base.php

// DESCRIPTION : 

/*  
    Vérifie la présence de la PTO et de l'ONT dans le dossier du client
*/

// ARGUMENTS :

/*  
    $data :
        type : array
        description : fournit toutes les données du dossier et du compte client  
    
    $nameDatabase :
        type : chaine de caractère
        description : contient le nom de la base de données utilisée pour la recherche
    
*/

// RETOUR : 

/*  
    type : String
*/

function rule_check_PTO_ONT(array $data, $nameDatabase){

    // Si des données ont été passées à la fonction
    if(!empty($data)){

        // Pour chaque lot de données (données du dossier et/ou données du compte)
        foreach ($data as $item) {

            // Initialisation du retour par défaut
            $conclusion = 'Prises PTO et ONT - Un problème est survenu dans l\'acquisition des informations de l\'ONT et/ou du PTO';

            // Si les données sont celles du compte, on aura pas d'information sur le PTO et l'ONT 
            if($item[0][0]["OBJECT_TYPE_ID"] == "2091353054013993289"){
                $conclusion = 'Prises PTO et ONT - pas de résultat';
            }

            // Si les données sont celles du dossier
            if($item[0][0]["OBJECT_TYPE_ID"] == "8050748698013937493"){
                
                // Initialiser le timer
                $time_start = microtime(true);
                
                // Initialiser des variables
                $value = -1;
                $compteur = 0;
                
                // Vérifier qu'on peut récupérer des informations sur l'ONT par mis les données
                for($i = 0; $i < count($item[2]); $i++){
                    if($item[2][$i]["REF_NAME"] == "ONT"){
                        // Récupération du numéro du rang de la ligne qui nous intéresse
                        $value = $i;
                    }
                }

                // Si la ligne ciblée existe dans les données du dossier
                if($item[2][$value]["REF_NAME"]){
                    // Si le champs référence de la ligne est vide
                    if($item[2][$value]["REFERENCE"] == ''){
                        // Incrémenter un compteur
                        $compteur += 1;
                    }
                    
                    // Reinitialiser le numéro de ligne
                    $value = -1;

                    // Faire la même chose pour le PTO 
                    for($j = 0; $j < count($item[1]); $j++){
                        if($item[2][$j]["REF_NAME"] == "PTO identified"){
                            $value = $j;
                        }                    
                    }

                    if($item[2][$value]["REFERENCE"] == ''){
                        $compteur += 2;
                    }

                    
                    // En fonction du résultat du compteur, compléter l'affichage en conséquence
                    switch ($compteur) {
                        case 0 :
                            $affichage = "<div class='o-rule__m-container o-rule__m-container--green'>
                            <h4 class='o-rule__a-title'>Présence prise PTO et ONT - CONFORME</h4><nav class='o-rule__a-more'>Les deux prises existent dans le dossier</nav>";
                            $conclusion = 'Prises PTO et ONT - les deux prises existent dans le dossier';
                            
                            break;

                        case 1 :
                            $affichage = "<div class='o-rule__m-container o-rule__m-container--red'>
                            <h4 class='o-rule__a-title'>Présence prise PTO et ONT - NON CONFORME</h4><nav class='o-rule__a-more'>Prise ONT manquante dans le dossier</nav>";
                            $conclusion = 'Prises PTO et ONT - prise ONT manquante dans le dossier';
                            break;

                        case 2 :
                            $affichage = "<div class='o-rule__m-container o-rule__m-container--red'>
                            <h4 class='o-rule__a-title'>Présence prise PTO et ONT - NON CONFORME</h4><nav class='o-rule__a-more'>Prise PTO manquante dans le dossier</nav>";
                            $conclusion = 'Prises PTO et ONT - prise PTO manquante dans le dossier';
                            break;

                        case 3 :
                            $affichage = "<div class='o-rule__m-container o-rule__m-container--red'>
                            <h4 class='o-rule__a-title'>Présence prise PTO et ONT - NON CONFORME</h4><nav class='o-rule__a-more'>Prises  PTO et ONT manquantes dans le dossier</nav>";
                            $conclusion = 'Prises PTO et ONT - prises  PTO et ONT manquantes dans le dossier';
                            break;

                        default:
                            $affichage = "<div class='o-rule__m-container o-rule__m-container--red'>
                            <h4 class='o-rule__a-title'>Présence prise PTO et ONT - NON CONFORME</h4><nav class='o-rule__a-more'>Un problème est survenu dans l'acquisition des informations de l'ONT et/ou du PTO</nav>";
                            
                            break;
                    }

                    // Récupération du temps écoulé depuis le lancement du premier timer
                    $time_end = microtime(true);
                    $time = round(($time_end - $time_start) * 1000, 3);

                    $affichage .= "<div class='timer'>Temps de réponse : $time ms</div></div></div>";

                    // Afficher
                    echo $affichage;
                                
                }
            }
        }

        return $conclusion;

    }
    else{
        $conclusion = 'Prises PTO et ONT : pas de résultat';
        return $conclusion;
    }
}

?>