<?php
// FICHIER APPELE DANS : header.php

// IMPORT :

// Model
@require "./model/resultSearch/Column.php";
@require "./model/resultSearch/f_searchByID.php";
@require "./model/resultSearch/f_filterSearch.php";
@include './model/formateFunctions.php';
// Analyse tools
@include "./analyse/_base.php";
@include "./analyse/test_unicite_site_support.php";
// Config
include_once './config/database_functions.php';
include_once "./config/test_connection_database.php";

// Script
@include './script/saveInHistory.php';





// DESCRIPTION : 

/*  
    Fichier de pilotage de la recherche. En fonction des paramètres sélectionnés à l'IHM et aux données renseignées, l'utilisateur sera
    obtiendra l'affichage attendu ou un message d'erreur. Ce fichier permet de mettre en place les connexions aux base de données et de 
    gérer l'affichage des résultats de la recherche. 
*/




// Javascript
echo "<script type='text/javascript' src='./public/js/search.js'></script>";
echo "<script type='text/javascript' src='./public/js/functions.js'></script>";

// Global variable
$listResult = null;

//Check if form was submitted
// TODO : Ajouter la connexion en même temps aux deux BDD
if(isset($_POST['submitSearch'])){

    if(trim($_POST['searchBar']) != ""){
        
        // Connect with the database checked
        // Variable to identify what connection the user has checked
        
        $time_start = microtime(true);

        $combinaisonHorsRip = 0;
        $combinaisonRip = 0;
        


        // Check what type of search is selected
        // SEARCH BY ID
        if ($_POST['typeSearch'] == "search-byID") {
            // Get the information asked (ID ou ND/VIA)
            // id test : 9160676156414853441
            // ID test : 912687110381324107

            @include './view/searchModeByID.php';

        } 
        elseif ($_POST['typeSearch'] == "search-byNDVIA") {

            
            @include './view/searchModeNDVIA.php';   
                        
        } 
        elseif ($_POST['typeSearch'] == "search-perso") {
            /*
            Exemple :
            SELECT OBJECT_ID, PARENT_ID, OBJECT_TYPE_ID, NAME, DESCRIPTION 
            FROM nc_objects 
            WHERE object_id = 9160676156414853441
            */
            $request = str_replace("\n", '', trim($_POST['searchBar']));;
            if (filteredRequestOnlySelect($request) == false) {
                //var_dump(filteredRequestOnlySelect($request));
                $prepareRequest = $bdd->prepare($request);
                $prepareRequest->execute();
                $result = $prepareRequest->fetchAll(PDO::FETCH_ASSOC);
                // TODO : TRAITEMENT REQUEST
                echo "<h3 class='o-result__m-dataSearch'><span>Donnée recherchée sur ".$nameDatabase." : </span></br>". $request."</h3>";
                if (!empty($result)) {
                    echo "<div class='p-search__result'>";
                    echo "<table class='o-result p-search__result'>";
                    // For each array in my multidimentionnal array
                    $allKeys = array_keys($result[0]);
                    //echo "<tr class='o-result__row'>";
                    foreach($allKeys as $rowKey) {
                        echo "<th class ='o-result__header'>{$rowKey}</th>";
                    }
                    //echo "<tr class='o-result__row'>";
                    foreach ($result as $array) {

                        $index++;
                        // Get element in the first array
                        echo "<tr class='o-result__row'>";
                            foreach ($array as $key => $value) {
                                echo "<td class='o-result__column'>{$value}</td>";
                            }
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "</div>";

                    saveSearchResultInHistory(" $index");

                    // Start the analyse
                    //rule_automatic($id, $result);
                } else {
                    echo "<div class='o-alert'>La requête génère une erreur ou est vide. Vérifier la base de données sur laquelle vous avez effectué la requête ainsi que le type de recherche.</div>";
                    
                    saveSearchResultInHistory(" vide car la requête génère une erreur ou est vide.");
                
                }
            }

        }
        elseif ($_POST["typeSearch"] == "search-several") {
            echo "Test";
            // Start the analyse
            //rule_automatic($id, $listResult);
            saveSearchResultInHistory("");

        }  
        elseif($_POST['typeSearch'] == "search-GER"){

            @include './view/searchModePTGER.php';
            
        }
        elseif($_POST['typeSearch'] == "search-supportGER"){
            
            @include './view/searchModeSupportGER.php';            
        
        }else {
            echo "<div class='o-alert'>Sélectionner une option de recherche</div>";
        }
        $bdd = null;

        // Récupération du temps écoulé depuis le lancement du premier timer
        $time_end = microtime(true);
        $time = round(($time_end - $time_start) * 1000, 3);
        
        ?>
        <script>
            if(document.getElementById('temps_global')){
                let delay = <?php echo $time ?>;            
                document.getElementById('temps_global').textContent = "Temps d'éxécution global : "+(delay/1000).toFixed(3)+" secondes";
                document.getElementById('temps_global').style.display = 'block';
            }
        </script>
        <?php
    }
}
   
?>
