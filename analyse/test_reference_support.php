<?php

// Fonction assurant que la référence support d'un immeuble est conforme à la synthaxe attendue
// Renvoie true pour une synthaxe correcte et false pour une synthaxe incorrecte 

//Les attendus :
/*
- 16 caractères
- 4 parties délimitées par des '/'
- 1ere partie doit être 'IMB'
- 2eme partie doit être 5 caractères
- 3eme partie doit être 1 lettre
- 4eme partie doit être 4 caractères
*/
function isRefImmeubleCorrect($refSupport){

    $refSupportCorrect = false;

    $tailleRefSupport = strlen(trim($refSupport));



    if($tailleRefSupport != 16 ){
        echo "<div class='o-alert'>La référence support d'un immeuble doit comporter 16 caractères.</div>";
    }else{

        $tabPartieRefSupport = explode("/", $refSupport);
        

        if(count($tabPartieRefSupport) != 4){
            echo "<div class='o-alert'>La référence support doit comporter 4 champs séparés par des '/'.</div>";
        }
        else{

            if($tabPartieRefSupport[0] != "IMB"){
                echo "<div class='o-alert'>Le premier champs de la référence support pour un immeuble doit être 'IMB'.</div>";
            }
            else{
                if (strlen($tabPartieRefSupport[1]) != 5) {
                    echo "<div class='o-alert'>Le code INSEE doit comporter 5 caractères.</div>";
                }
                else {
                    if (!preg_match('/^[a-zA-Z]$/', $tabPartieRefSupport[2])) {
                        echo "<div class='o-alert'>Le troisième champs de la référence support doit être une lettre seule.</div>";
                    }
                    else {
                        if (strlen($tabPartieRefSupport[3]) != 4) {
                            echo "<div class='o-alert'>Le numéro d'ordre doit comporter 4 caractères.</div>";
                        }
                        else {
                            $refSupportCorrect = true;
                        }
                    }
                }
            }
        }
    }

    return $refSupportCorrect;
}

// Fonction assurant que la référence support d'un appui ft est conforme à la synthaxe attendue
// Renvoie true pour une synthaxe correcte et false pour une synthaxe incorrecte 

//Les attendus :
/*
- 13 caractères
- 2 parties délimitées par des '/'
- 1ere partie doit être 7 caractères
- 2eme partie doit être au moins 5 caractères
*/
function isRefAppuiFtCorrect($refSupport){

    $refSupportCorrect = false;

    $tailleRefSupport = strlen(trim($refSupport));



    if($tailleRefSupport < 13 ){
        echo "<div class='o-alert'>La référence support d'un appui-ft doit comporter 13 caractères.</div>";
    }else{

        $tabPartieRefSupport = explode("/", $refSupport);
        

        if(count($tabPartieRefSupport) != 2){
            echo "<div class='o-alert'>La référence support doit comporter 2 champs séparés par des '/'.</div>";
        }
        else{

            
            if (strlen($tabPartieRefSupport[1]) != 5) {
                echo "<div class='o-alert'>Le code INSEE doit comporter 5 caractères.</div>";
            }
            else {
                
                if (strlen($tabPartieRefSupport[0]) < 7) {
                    echo "<div class='o-alert'>Le numéro d'ordre doit comporter au moins 7 caractères.</div>";
                }
                else {
                    $refSupportCorrect = true;
                }
            }
            
        }
    }

    return $refSupportCorrect;
}

// Fonction assurant que la référence support d'un appui erdf est conforme à la synthaxe attendue
// Renvoie true pour une synthaxe correcte et false pour une synthaxe incorrecte 

//Les attendus :
/*
- 13 caractères
- 2 parties délimitées par des '/'
- 1ere partie doit être 7 caractères
- 2eme partie doit être d'au moins 5 caractères
- 2eme partie doit commencée par un 'E'
*/
function isRefAppuiErdfCorrect($refSupport){

    $refSupportCorrect = false;

    $tailleRefSupport = strlen(trim($refSupport));



    if($tailleRefSupport < 13 ){
        echo "<div class='o-alert'>La référence support d'un appui-erdf doit comporter au moins 13 caractères.</div>";
    }else{

        $tabPartieRefSupport = explode("/", $refSupport);
        

        if(count($tabPartieRefSupport) != 2){
            echo "<div class='o-alert'>La référence support doit comporter 2 champs séparés par des '/'.</div>";
        }
        else{

            
            if (strlen($tabPartieRefSupport[1]) != 5) {
                echo "<div class='o-alert'>Le code INSEE doit comporter 5 caractères.</div>";
            }
            else {
                
                if (strlen($tabPartieRefSupport[0]) < 7) {
                    echo "<div class='o-alert'>Le numéro d'ordre doit comporter au moins 7 caractères.</div>";
                }
                else {

                    if(substr($tabPartieRefSupport[0], 0, 1) != 'E'){
                        echo "<div class='o-alert'>Le numéro d'ordre d'un appui erdf doit commencé par la lettre 'E'.</div>";
                    }
                    else{
                        $refSupportCorrect = true;
                    }
                    
                }
            }
            
        }
    }

    return $refSupportCorrect;
}


// Fonction assurant que la référence support d'une chambre est conforme à la synthaxe attendue
// Renvoie true pour une synthaxe correcte et false pour une synthaxe incorrecte 

//Les attendus :
/*
- 11 caractères
- 2 parties délimitées par des '/'
- 1ere partie doit être d'au moins 5 caractères
- 2eme partie doit être 5 caractères
*/
function isRefChambreCorrect($refSupport){

    $refSupportCorrect = false;

    $tailleRefSupport = strlen(trim($refSupport));



    if($tailleRefSupport < 11 ){
        echo "<div class='o-alert'>La référence support d'une chambre doit comporter au moins 11 caractères.</div>";
    }else{

        $tabPartieRefSupport = explode("/", $refSupport);
        

        if(count($tabPartieRefSupport) != 2){
            echo "<div class='o-alert'>La référence support doit comporter 2 champs séparés par des '/'.</div>";
        }
        else{

            
            if (strlen($tabPartieRefSupport[1]) != 5) {
                echo "<div class='o-alert'>Le code INSEE doit comporter 5 caractères.</div>";
            }
            else {
                
                if (strlen($tabPartieRefSupport[0]) < 5) {
                    echo "<div class='o-alert'>Le numéro d'ordre doit comporter au moins 5 caractères.</div>";
                }
                else {
                    $refSupportCorrect = true;
                }
            }
            
        }
    }

    return $refSupportCorrect;
}


// Fonction assurant que la référence support d'une armoire est conforme à la synthaxe attendue
// Renvoie true pour une synthaxe correcte et false pour une synthaxe incorrecte 

//Les attendus :
/*
- 11 caractères
- 2 parties délimitées par des '/'
- 1ere partie doit être d'au moins 5 caractères
- 2eme partie doit être  5 caractères
*/
function isRefArmoireCorrect($refSupport){

    $refSupportCorrect = false;

    $tailleRefSupport = strlen(trim($refSupport));



    if($tailleRefSupport < 11 ){
        echo "<div class='o-alert'>La référence support d'une armoire doit comporter au moins 11 caractères.</div>";
    }else{

        $tabPartieRefSupport = explode("/", $refSupport);
        

        if(count($tabPartieRefSupport) != 2){
            echo "<div class='o-alert'>La référence support doit comporter 2 champs séparés par des '/'.</div>";
        }
        else{

            
            if (strlen($tabPartieRefSupport[1]) < 5) {
                echo "<div class='o-alert'>Le code INSEE doit comporter 5 caractères.</div>";
            }
            else {
                
                if (strlen($tabPartieRefSupport[0]) < 5) {
                    echo "<div class='o-alert'>Le numéro d'ordre doit comporter au moins 5 caractères.</div>";
                }
                else {
                    $refSupportCorrect = true;
                }
            }
            
        }
    }

    return $refSupportCorrect;
}



?>