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

function rule_check_NAME ($id, $data, $bdd) {
    $listIds = [];
    if (!$id) {
        return;

    }
    
    $requestLignes = "select r.OBJECT_ID
        from nc_attributes a, nc_params p, nc_list_values l, RRE_CUSTOMER_ACCOUNT r
        where a.attr_id = p.attr_id and l.list_value_id(+) = p.list_value_id AND r.IAR_NDFICTIF = p.VALUE and p.object_id = '{$id}' AND a.name='IAR'";
    $prepareRequestLignes = $bdd->prepare($requestLignes);
    $prepareRequestLignes->execute();
    $resultsLignes = $prepareRequestLignes->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($resultsLignes) > 1) {
        foreach ($resultsLignes as $value) {
            $listIds[] = $value["OBJECT_ID"];
        }
    }


    $string = "";

    if ($resultsLignes == 0 ) { 
       
    }

    elseif (count($listIds) > 0) {
        $string .= "<div class='o-rule__m-container o-rule__m-container--red'>
        <h4 class='o-rule__a-title'>Vérification Compte Client - ERREUR</h4>
        <nav class='o-rule__a-more '>
        Liste des ID de tous les comptes en doublons :
        <ul>";
        foreach ($listIds as $value) {
            $string .= "<li>".$value."</li>";
        }
        $string .= "</ul></nav></div>";
    }
    else {
        $string .= "<div class='o-rule__m-container o-rule__m-container--green'>
                    <h4 class='o-rule__a-title'>Vérification Compte Client - CONFORME</h4>
                    <nav class='o-rule__a-more'>Compte client unique</nav>
                    </div>";
    }
    echo $string;
    
}


function test($array, $espace = 0) {
    foreach($array as $index => $value) {  
        echo "<br><div style='margin-right= $espace px'>[$index =>";  
        if(is_array($value)){
            test($value, $espace + 20);
        } else {
           
            echo ($value ?: 'null').""; 
        }
        echo "]</div>";
    }
}

?>