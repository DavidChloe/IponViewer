<?php
// FICHIER APPELE DANS : ControllerHeader.php, qdController.php

// DESCRIPTION :
/*
    Fichier comprenant des fonctions de pré-formatage 
*/










// FONCTION UTILISEE DANS : searchModePTGER.php

// DESCRIPTION : 

/*  
    Permet de formater une chaine de caractère pour son utilisation ultérieur. Spécifique au mode de recherche par id ipon.
*/

// ARGUMENTS :

/*  
    $database :
        type : String
        description : récupère le nom de la base de données sur laquelle est effectuée la recherche
    
    $string :
        type : String
        description : récupère la chaine de caractère passée dans la barre de recherche
    
*/

// RETOUR : 

/*  
    type : String
    description : renvoie la chaine formater, prête à être utilisé dans la requête.
*/


function formateStringForRequest($database, $string){

    switch($database)
    {
        case 'RIP' :
            // Supprimer les espaces un début et à la fin de la chaîne de caractère
            // Supprimer tout caractère qui n'est pas une lettre, un chiffre ou un retour à la ligne
            $string = preg_replace("/[^A-Za-z0-9\n\r]/", "", trim($string));
            // Remplacer les caractères de retour à la ligne par ','
            $string = str_replace("\r\n", "','", $string);
            // Remplacer les espaces dans la chaine par ','
            $string = str_replace(" ", "','", $string);
            // Remplacer les '915 par 'R915
            $string = str_replace("'915","'R915",$string);
            // Supprimer toutes les lettres majuscules de A à Q et de S à Z et les lettres minuscules entre a et z
            $string = preg_replace("/[A-QS-Za-z]/","",$string);
        break;

        case 'IPON':
            // Supprimer les espaces un début et à la fin de la chaîne de caractère
            // Supprimer tout caractère qui n'est pas une lettre, un chiffre ou un retour à la ligne
            $string = preg_replace("/[^A-Za-z0-9\n\r]/", "", trim($string));
            // Remplacer les caractères de retour à la ligne par ','
            $string = str_replace("\r\n", "','", $string);
            // Remplacer les espaces dans la chaine par ','
            $string = str_replace(" ", "','", $string);
            // Supprimer toutes les lettres de la chaine
            $string = trim(preg_replace("/[A-Za-z]/","",$string));
        break;


        default :
            // Supprimer les espaces un début et à la fin de la chaîne de caractère
            // Supprimer tout caractère qui n'est pas une lettre, un chiffre ou un retour à la ligne
            $string = preg_replace("/[^A-Za-z0-9\n\r]/", "", trim($string));
            // Remplacer les caractères de retour à la ligne par ','
            $string = str_replace("\r\n", "','", $string);
            // Remplacer les espaces dans la chaine par ','
            $string = str_replace(" ", "','", $string);
            // Supprimer toutes les lettres de la chaine
            $string = trim(preg_replace("/[A-Za-z]/","",$string));
        break;
    }

    return $string;

}








// FONCTION UTILISEE DANS : searchModePTGER.php

// DESCRIPTION : 

/*  
    Permet de formater la requête récupérant les informations côté Géoreso
*/

// ARGUMENTS :

/*  
    $idsForReqGER :
        type : String
        description : récupère les ids passées dans la barre de recherche après formatage
*/

// RETOUR : 

/*  
    type : String
    description : renvoie la requête formatée, prête à être executée.
*/


function requestForGER($idsForReqGER){
    $req = "SELECT REPLACE(type_site, 'ftth_site_', '') as type_site,id_metier_site,ref_pt,objectid_ipon
    FROM georeso.ftth_point_technique_attr
    WHERE objectid_ipon in ('".$idsForReqGER."')
    order by ref_pt";

    return $req;
}



// FONCTION UTILISEE DANS : searchModePTGER.php

// DESCRIPTION : 

/*  
    Permet de formater la requête récupérant les informations côté Ipon
*/

// ARGUMENTS :

/*  
    $idsForIPON :
        type : String
        description : récupère les ids passées dans la barre de recherche après formatage
*/

// RETOUR : 

/*  
    type : String
    description : renvoie la requête formatée, prête à être executée.
*/

function requestForIPON($idsForIPON){
    $req = "WITH cte AS (
        SELECT NAME,OBJECT_ID,PARENT_ID,OBJECT_TYPE_ID
        FROM NC_OBJECTS
        WHERE OBJECT_ID IN ('".$idsForIPON."')
    )SELECT n.NAME AS SITE, c.NAME Num_PT, c.OBJECT_ID, c.PARENT_ID,c.OBJECT_TYPE_ID FROM NC_OBJECTS n
    JOIN  cte c ON c.PARENT_ID = n.OBJECT_ID
    WHERE n.OBJECT_ID IN (SELECT PARENT_ID FROM cte)
    ORDER BY NUM_PT";

    return $req;
}






// FONCTION UTILISEE DANS : searchModeSupportGER.php

// DESCRIPTION : 

/*  
    Permet de formater la requête récupérant les informations d'un/des site(s) dans Ipon associées à un id métier
*/

// ARGUMENTS :

/*  
    $id_metier :
        type : String
        description : id métier d'une site support
*/

// RETOUR : 

/*  
    type : String
    description : renvoie la requête formatée, prête à être executée.
*/
function requestSiteExisteDansIpon($id_metier)
{
    $req = "SELECT y.IX_KEY AS ID_METIER, y.OBJECT_ID AS OBJECT_ID_IPON
            FROM NC_PARAMS_IX y
            JOIN NC_OBJECTS x
            ON y.OBJECT_ID= x.OBJECT_ID
            WHERE y.ATTR_ID = 9130434867813009957 AND y.IX_KEY ='$id_metier'
        "
    ;

    return $req;
}





// FONCTION UTILISEE DANS : searchModeSupportGER.php

// DESCRIPTION : 

/*  
    Permet de formater la requête récupérant les informations d'un/des PT dans Ipon associées à un id métier
*/

// ARGUMENTS :

/*  
    $id_metier :
        type : String
        description : id métier d'une site support
*/

// RETOUR : 

/*  
    type : String
    description : renvoie la requête formatée, prête à être executée.
*/
function requestPtIpon($id_metier)
{
    $req = "SELECT x.OBJECT_ID AS ID_IPON_PT, x.NAME AS PT, y.IX_KEY AS SITE_TECHNIQUE
            FROM NC_OBJECTS x
            JOIN NC_PARAMS_IX y
            ON y.OBJECT_ID=x.OBJECT_ID
            WHERE x.PARENT_ID = (SELECT OBJECT_ID FROM NC_PARAMS_IX WHERE ATTR_ID = 9130434867813009957 AND IX_KEY ='$id_metier')
            AND y.ATTR_ID = 9124707191013891891
        "
    ;

    return $req;
}







// FONCTION UTILISEE DANS : searchModeSupportGER.php, searchModePTGER.php

// DESCRIPTION : 

/*  
    Permet de formater le résumé des résultats vus de géoreso pour les modes de recherhe PT GER et SUpport GER
*/

// ARGUMENTS :

/*  
    $nbResults :
        type : String
        description : le nombre de résultats obtenus

    $nbGreenResults :
        type : String
        description : le nombre de résultats conformes obtenus

    $nbOrangeResults :
        type : String
        description : le nombre de résultats avec des incohérences obtenus

    $nbRedResults :
        type : String
        description : le nombre de résultats sans correspondance obtenus
*/

// RETOUR : 

/*  
    type : String
    description : renvoie la structure html du résumé à afficher.
*/
function createResume($nbResults, $nbGreenResults, $nbOrangeResults, $nbRedResults){
    $resume="<section class='recapResult'>
                    <div class = 'recapResult_nbResult'>
                        Nombre de résultats : $nbResults
                    </div>
                    <div class='container'>
                        <div class = 'recapResult_nbResultCoherents'>Nombre de résultats cohérents :
                            <span class = 'greenColor'>
                                $nbGreenResults
                            </span>
                        </div>
                        <div class = 'recapResult_nbResultIncoherents'>Nombre de résultats sans correspondance :
                            <span class = 'orangeColor'>
                                $nbOrangeResults
                            </span> </div>
                        <div class = 'recapResult_nbResultSansCorrespondance'>Nombre de résultats incohérents :
                            <span class = 'redColor'>
                                $nbRedResults
                            </span>
                        </div>
                    </div>
            </section>";

    return $resume;
}





// FONCTION UTILISEE DANS : searchModeSupportGER.php, searchModePTGER.php

// DESCRIPTION : 

/*  
    Permet de formater une ligne complète du tableau des informations des modes de recherche PT GER et Support GER
*/

// ARGUMENTS :

/*  
    $index :
        type : int
        description : le numéro de la ligne

    $idIPON :
        type : String
        description : l'id métier associé à la référence du type support dans Ipon 

    $ptIPON :
        type : String
        description : la référence du PT dans Ipon 

    $supportIPON :
        type : String
        description : la référence du type support dans Ipon


    $ptGER :
        type : String
        description : la référence du type support dans Géoreso

    $supportGER :
        type : String
        description : l'id métier associé à la référence du type support dans Géoreso

    $typeSiteGER :
        type : String
        description : le nom du site support

    $commentaire :
        type : String
        description : l'analyse des résultats affichées sur la ligne du tableau 

    $couleurCommentaire :
        type : String
        description : le nom de la couleur du commentaire
*/

// RETOUR : 

/*  
    type : String
    description : renvoie la structure html du résumé à afficher.
*/
function createLineTab($index, $idIPON, $ptIPON, $supportIPON, $ptGER, $supportGER, $typeSiteGER, $commentaire, $couleurCommentaire){
    $lineTab = "<tr class='o-result__row'>
        <td class='o-result__column'>{$index}</td>
        <td class='o-result__column'>{$idIPON}</td>
        <td class='o-result__column'>{$ptIPON}</td>
        <td class='o-result__column'>{$supportIPON}</td>
        <td class='o-result__column'></td>
        <td class='o-result__column'>{$ptGER}</td>
        <td class='o-result__column'>{$supportGER}</td>
        <td class='o-result__column'>{$typeSiteGER}</td>
        <td class='o-result__column' style='color: $couleurCommentaire;'>{$commentaire}</td>
    </tr>";

    return $lineTab;
}


// Même chose que la fonction createLineTab mais sans l'index, la ligne du tableau n'a pas de balise de début
// Cette fonction permet également de créer le formulaire permettant de lancer la recherche d'un site support dans le mode de recherche Support GER à partir du tableau
function createLineTab2($idIPON, $ptIPON, $supportIPON, $ptGER, $supportGER, $typeSiteGER, $commentaire, $couleurCommentaire){

    $name = '';

    switch($typeSiteGER)
    {
        // Pour une recherche sur un immeuble
        case 'immeuble' :
            $name = 'gerTypeSearch-Immeuble';
        break;
        // Pour une recherche sur un appui ft
        case 'appui_ft' :
            $name = 'gerTypeSearch-AppuiFt';
        break;
        // Pour une recherche sur un appui erdf
        case 'appui_erdf' :
            $name = 'gerTypeSearch-AppuiErdf';
        break;
        // Pour une recherche sur une chambre
        case 'chambre' :
            $name = 'gerTypeSearch-Chambre';
        break;
        // Pour une recherche sur une armoire
        case 'armoire' :
            $name = 'gerTypeSearch-Armoire';
        break;
        default : echo "<div class='o-alert'>Le support sélectionné n'est pas implémenté : $typeSiteGER</div>";
        break;
    }

    // Compléter la ligne et ajouter le formulaire sur la case de la référence du site support
    $lineTab = "
        <td class='o-result__column'>{$idIPON}</td>
        <td class='o-result__column'>{$ptIPON}</td>
        <td class='o-result__column'>{$supportIPON}</td>
        <td class='o-result__column'></td>
        <td class='o-result__column'>{$ptGER}</td>
        <td class='o-result__column'>
            <form action='' method='post'>
                <input type='submit' value='$supportGER' name='submitSearch'>
                <input type='hidden' value='search-supportGER' name='typeSearch'>
                <input type='hidden' value='$supportGER' name='searchBar'>
                <input type='hidden' value='$name' name='supportGerTypeSearch' >
            </form>
        </td>
        <td class='o-result__column'>$typeSiteGER</td>


        <td class='o-result__column' style='color: $couleurCommentaire;'>{$commentaire}</td>
    </tr>";

    return $lineTab;
}






// FONCTION UTILISEE DANS : historySearch.php

// DESCRIPTION : 

/*  
    Permet de récupérer le mode de recherche associé à une chaine de caractère définie
*/

// ARGUMENTS :

/*  
    $optionSelect :
        type : String
        description : l'appelation dans les l'historique
*/

// RETOUR : 

/*  
    type : String
    description : renvoie le nom de l'option associé à l'intitulé.
*/
function getOptionValueTypeSearch($optionSelect){

    $name = "";

    switch($optionSelect)
    {
        case 'Recherche par ND-VIA': $name = "search-byNDVIA";
        break;
        case 'Recherche par ID': $name = "search-byID";
        break;
        case 'Recherche par PT-GER - RIP':
        case 'Recherche par PT-GER - IPON': $name = "search-GER";
        break;
        case 'Recherche par support GER - Immeuble':
        case 'Recherche par support GER - Chambre':
        case 'Recherche par support GER - Armoire':
        case 'Recherche par support GER - Apui ERDF':
        case 'Recherche par support GER - Appui FT': $name = "search-supportGER";
        break;
        default : $name = "";
        break;

    }

    return $name;

}

// Même chose que pour getOptionValueTypeSearch() mais pour les type de site support
function getOptionValueSupportGerTypeSearch($optionSelect){

    $name = "";

    switch(trim($optionSelect))
    {
        case 'Recherche par support GER - Immeuble': $name = "gerTypeSearch-Immeuble";
        break;
        case 'Recherche par support GER - Chambre': $name = "gerTypeSearch-Chambre";
        break;
        case 'Recherche par support GER - Armoire': $name = "gerTypeSearch-Armoire";
        break;
        case 'Recherche par support GER - Appui ERDF': $name = "gerTypeSearch-AppuiErdf";
        break;
        case 'Recherche par support GER - Appui FT': $name = "gerTypeSearch-AppuiFt";
        break;
        default : $name = "";
        break;

    }

    return $name;

}


// Même chose que pour getOptionValueTypeSearch() mais pour la base de données sélectionnée
function getDatabaseValueGer($optionSelect){

    $name = "";

    switch(trim($optionSelect))
    {
        case 'Recherche par PT-GER - RIP': $name = "RIP";
        break;
        case 'Recherche par PT-GER - IPON': $name = "IPON";
        break;
        default : $name = "";
        break;

    }

    return $name;

}



?>