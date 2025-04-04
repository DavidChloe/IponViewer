<?php

// FICHIER APPELE DANS : formateFunctionsQD.php

// DESCRIPTION :
/*
    Fichier comprenant les fonction de formatage des requêtes pour la QD
*/


// Intitulé de requête pour récupérer parmis les OBJECT_TYPE_ID des immeubles, les nom dans le champs NAME ne commençant pas par 'IMB/'
// Prend en paramètre le nombre de ligne sur lesquelles on veut faire la recherche
function requeteNomImmeuble($num_fetch_row)
{
    $req = "SELECT * FROM NC_OBJECTS WHERE OBJECT_TYPE_ID=6031063083013850058 AND NAME NOT LIKE 'IMB/%'";

    if($num_fetch_row > 0 && $num_fetch_row != null){
        $req .= "FETCH FIRST $num_fetch_row ROWS ONLY";
    }

    return $req;
}


?>