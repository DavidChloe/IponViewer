<?php

// FICHIER APPELE DANS : loadFunctionCheck_AT_Fiche_Navette.php

// FONCTION UTILISEE DANS : loadFunctionCheck_AT_Fiche_Navette.php


// DESCRIPTION : 

/*  
    Cette fonction vérifie que le nom de l'AT attribué dans le compte/dossier du client correspond bien à celui 
    figurant dans dans la fiche navette associée au client.
*/


// ARGUMENTS :
/* 
    $iar :
        type : chaine de caractère
        description : contient le ND ou le VIA passé dans la barre de recherche par l'utilisateur
    
    $refAT :
        type : chaine de caractère
        description : contient le nom de l'AT dans le dossier et/ou le compte client
    
    $database : 
        type : mixed
        description : instance de la classe PDO permettant la connexion et la communication avec une base de données
*/

// RETOUR : 
    
/*
    type : String (chaine de caractères)
*/

function checkATinFicheNavette($iar, $refAT, $database){

    // IMPORT :
    include '../script/areArraysEmptyRecursive.php';
    include '../config/database_functions.php';

    // On vérifie qu'une référence à bien été passé à la fonction
    if(trim($refAT) != ""){
        
        // Initialiser le timer
        $time_start = microtime(true);

        // Si la base de données sélectionnée est hors RIP
        if($database == 'IPON'){
            
            // Création de la PDO pour une connexion à la base de données IPON
            $connexionBdd = DatabaseConnectionPool::createConnection('Ipon');
            $bdd = DatabaseConnectionPool::getPDO('Ipon');

        }
        // Sinon, si c'est RIP
        else {

            // Création de la PDO pour une connexion à la base de données IPON RIP
            $connexionBdd = DatabaseConnectionPool::createConnection('IponRip');
            $bdd = DatabaseConnectionPool::getPDO('IponRip');
        }

        // Initialisation d'un tableau vide
        $resultsReq = [];

        // Si la connexion à la base de données a pu être effectuée
        if($connexionBdd[0]){

            // Récupérer le nom de l'AT dans la fiche navette associé à l'IAR du client
            $req = "select RRE_AT.NAME from RRE_AT where IAR_NDFICTIF = '{$iar}'"; //0590214454 KO - 0555060345 OK - 0471472467 doublon
            $prepareReq = $bdd->prepare($req);
            $prepareReq->execute();
            $resultsReq = $prepareReq->fetchAll(PDO::FETCH_ASSOC);

            // Fermer la connexion à la base de données utilisée
            if($database == 'IPON'){
                DatabaseConnectionPool::closeConnection('Ipon');
            }
            else{
                DatabaseConnectionPool::closeConnection('IponRip');
            }
        }

        // Initialisation du résumé qui sera renvoyé par la fonction pour figurer dans les traces
        $resume = "";
               
        // Si on a pas obtenu de résultat à la requete 
        if(empty($resultsReq)){
            // Afficher un message d'erreur
            $affichage = "
                <div class='o-rule__m-container o-rule__m-container--red'>
                    <h4 class='o-rule__a-title'>Règle supplémentaire n°1 : <br> Fiche Navette - NON CONFORME</h4>
                    <nav class='o-rule__a-more'>
                        Aucune fiche navette trouvée pour l'IAR : $iar
                    </nav>
                ";
            
            // Compléter le résumé
            $resume = "Aucune fiche navette détectée";
        }
        // Sinon
        else{
            // récupérer la taille du tableau
            $tailleRes = count($resultsReq);
            // Si le tableau ne contient pas qu'un seul nom d'AT  
            if($tailleRes != 1){
                // Afficher un message d'erreur
                $affichage = "
                <div class='o-rule__m-container o-rule__m-container--red'>
                    <h4 class='o-rule__a-title'>Règle supplémentaire n°1 : <br> AT Fiche Navette - NON CONFORME</h4>
                    <nav class='o-rule__a-more'>
                        Votre IAR n'est pas attribué à une seule fiche navette (IAR existant dans $tailleRes fiches navette)
                    </nav>
                ";
                // Compléter le résumé
                $resume = "Plusieur fiches navette détectées pour cet IAR (= $tailleRes)";
            }
            // Sinon 
            else{
                // Si on a eu un résultat mais qu'il ne contient pas de nom d'AT car le champs AT est vide dans la fiche navette
                if(areArraysEmptyRecursive($resultsReq)){
                    // Afficher un message d'erreur
                    $affichage = "
                    <div class='o-rule__m-container o-rule__m-container--red'>
                        <h4 class='o-rule__a-title'>Règle supplémentaire n°1 : <br> AT Fiche Navette - NON CONFORME</h4>
                        <nav class='o-rule__a-more'>
                            Aucun AT détecté dans la fiche navette : le champs est vide
                        </nav>
                    ";
        
                    // Compléter le résumé
                    $resume = "Aucun AT détecté dans la fiche navette";
                }
                // Sinon
                else{
                    // Si on a bien un nom d'AT dans le tableau mais que la référence qui lui ai associé n'est pas la même que la référence du dossier/compte client
                    if($resultsReq[0]['NAME'] != $refAT){
                        // Afficher un message d'erreur
                        $affichage = "
                        <div class='o-rule__m-container o-rule__m-container--red'>
                            <h4 class='o-rule__a-title'>Règle supplémentaire n°1 : <br> AT Fiche Navette - NON CONFORME</h4>
                            <nav class='o-rule__a-more'>
                                Le nom de l'AT dans le dossier/compte ne correspond pas à celui de la fiche navette
                            </nav>
                        ";
                        // Compléter le résumé
                        $resume = "Nom de l'AT différent dans le dossier/compte et dans la fiche navette";
                    }
                    // Sinon, si tout est bon
                    else{
                        // Afficher un message 
                        $affichage = "
                        <div class='o-rule__m-container o-rule__m-container--green'>
                            <h4 class='o-rule__a-title'>Règle supplémentaire n°1 : <br> AT Fiche Navette - CONFORME</h4>
                            <nav class='o-rule__a-more'>
                                Le nom de l'AT dans le dossier/compte correspond à celui de la fiche navette
                            </nav>
                        ";
                        // Compléter le résumé            
                        $resume = "Nom de l'AT correspond dans le dossier/compte et dans la fiche navette";
                    }

                }

                
            }

        }

        // Récupération du temps écoulé depuis le lancement du premier timer
        $time_end = microtime(true);
        $time = round(($time_end - $time_start), 2);

        
        
        // Ajouter le temps d'éxécution de la fonction
        $affichage .= "<div class='timer'>Temps de réponse : $time s</div></div>";

        // Afficher
        echo $affichage;

        return $resume;
    }
    else{
        return "Pas d'AT dans la fiche navette";
    }
}







?>