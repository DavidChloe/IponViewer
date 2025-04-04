<?php

// FICHIER APPELE DANS : header.php, qdResults.php, chech_AT_FICHE_NAVETTE.php

// FONCTION UTILISEE DANS : chech_AT_FICHE_NAVETTE.php, searchModeByID.php

// DESCRITPION :

/*

    Cette fonction permet de vérifier récursivement si tous les tableaux multidimensionnels d'un tableau donné sont vides.

*/


// ARGUMENTS

/*
    $array : 
        type : tableau
        descritption : tableau contenant potentiellement d'autres tableaux  

*/


// RETOUR : 

/*
    type : Booléen
    description : un retour à true signifie que tous les tableaux sont vides alors que false signifie qu'au moins 1 tableau n'est pas vide
*/


function areArraysEmptyRecursive(array $array): bool {
    foreach ($array as $value) {
        if (is_array($value)) {
            if (!areArraysEmptyRecursive($value)) {
                return false;
            }
        } else {
            if (!empty($value)) {
                return false;
            }
        }
    }
    
    return true;
}




?>