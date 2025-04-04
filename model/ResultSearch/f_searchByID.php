<?php
// IMPORTS
// Tools
@require "./script/_base.php";

// Execute the request by ID
function searchByID($listResult, $bdd, $nameDatabase, $ndVia){


        echo "<div class='p-search__o-containerResult'>";
        // If the all execute aren't empty, then display the result1
        if (!empty($listResult[0])) {
            echo "<table class='o-result p-search__result'>";
            echo "<caption class='o-result__caption'> NC_objects : type objet</caption>";
            $allKeys = array_keys($listResult[0][0]);
            //echo "<tr class='o-result__row'>";
            foreach($allKeys as $rowKey) {
                echo "<th class ='o-result__header'>{$rowKey}</th>";
            }
            //echo "<tr class='o-result__row'>";
            foreach ($listResult[0] as $array) {
                    // Get element in the first array
                    echo "<tr class='o-result__row'>";
                        foreach ($array as $key => $value) {
                            if ($key == 'OBJECT_ID')
                            {
                                if($nameDatabase == 'IPON')
                                {
                                    echo "<td class='o-result__column'><a href='https://ipon-ihm.sso.francetelecom.fr/common/uobject.jsp?object=$value' target='_blank'>{$value}</a></td>";
                                }
                                elseif($nameDatabase == 'RIP')
                                {
                                    echo "<td class='o-result__column'><a href='https://iponrip-ihm.sso.francetelecom.fr/common/uobject.jsp?object=$value' target='_blank'>{$value}</a></td>";
                                }
                                else {
                                    echo "<td class='o-result__column'>{$value}</td>";
                                }
                            }
                            else
                            {
                                echo "<td class='o-result__column'>{$value}</td>";
                            }
                            
                        }
                    echo "</tr>";
                }
            echo "</table>";
        } else {
            echo "<table class='o-result p-search__result'>";
            echo "<caption class='o-result__caption'>NC_objects : type objet</caption>";
            echo "<tr class='o-result__row'><td class ='o-result__column'>Vide</td></tr>";
            echo "</table>";
        }

        if (!empty($listResult[1])) {
            echo "<table class='o-result p-search__result'>";
            echo "<caption class='o-result__caption'>NC_attributes : Caractéristiques principales de l'élément recherché</caption>";
            $allKeys = array_keys($listResult[1][0]);
            //echo "<tr class='o-result__row'>";
            foreach($allKeys as $rowKey) {
                echo "<th class ='o-result__header'>{$rowKey}</th>";
            }
            //echo "<tr class='o-result__row'>";
            foreach ($listResult[1] as $array) {
                    // Get element in the first array
                    echo "<tr class='o-result__row'>";
                        foreach ($array as $key => $value) {
                            echo "<td class='o-result__column'>{$value}</td>";
                        }
                    echo "</tr>";
                }
            echo "</table>";
        } else {
            echo "<table class='o-result p-search__result'>";
            echo "<caption class='o-result__caption'>NC_attributes : Caractéristiques principales de l'élément recherché</caption>";
            echo "<tr class='o-result__row'><td class ='o-result__column'>Vide</td></tr>";
            echo "</table>";
        }

        if (!empty($listResult[2])) {
            echo "<table class='o-result p-search__result'>";
            echo "<caption class='o-result__caption'>NC_References : Objets asssociés</caption>";
            $allKeys = array_keys($listResult[2][0]);
            //echo "<tr class='o-result__row'>";
            foreach($allKeys as $rowKey) {
                echo "<th class ='o-result__header'>{$rowKey}</th>";
            }
            //echo "<tr class='o-result__row'>";
            foreach ($listResult[2] as $array) {
                    // Get element in the first array
                    echo "<tr class='o-result__row'>";
                        foreach ($array as $key => $value) {
                            echo "<td class='o-result__column'>{$value}</td>";
                        }
                    echo "</tr>";
                }
            echo "</table>";
        } else {
            echo "<table class='o-result p-search__result'>";
            echo "<caption class='o-result__caption'>NC_References : Objets asssociés</caption>";
            echo "<tr class='o-result__row'><td class ='o-result__column'>Vide</td></tr>";
            echo "</table>";
        }

        if (!empty($listResult[3])) {
            echo "<table class='o-result p-search__result'>";
            echo "<caption class='o-result__caption'>Références inverses : objets utilisés pour la recherche</caption>";
            $allKeys = array_keys($listResult[3][0]);
            //echo "<tr class='o-result__row'>";
            foreach($allKeys as $rowKey) {
                echo "<th class ='o-result__header'>{$rowKey}</th>";
            }
            //echo "<tr class='o-result__row'>";
            foreach ($listResult[3] as $array) {
                    // Get element in the first array
                    echo "<tr class='o-result__row'>";
                        foreach ($array as $key => $value) {
                            echo "<td class='o-result__column'>{$value}</td>";
                        }
                    echo "</tr>";
                }
            echo "</table>";    
        } else {
            echo "<table class='o-result p-search__result'>";
            echo "<caption class='o-result__caption'>Références inverses : objets utilisés pour la recherche</caption>";
            echo "<tr class='o-result__row'><td class ='o-result__column'>Vide</td></tr>";
            echo "</table>";
        }

        if (!empty($listResult[4])) {
            echo "<table class='o-result p-search__result'>";
            echo "<caption class='o-result__caption'>Parent de l'objet recherché</caption>";
            $allKeys = array_keys($listResult[4][0]);
            //echo "<tr class='o-result__row'>";
            foreach($allKeys as $rowKey) {
                echo "<th class ='o-result__header'>{$rowKey}</th>";
            }
            //echo "<tr class='o-result__row'>";
            foreach ($listResult[4] as $array) {
                    // Get element in the first array
                    echo "<tr class='o-result__row'>";
                        foreach ($array as $key => $value) {
                            echo "<td class='o-result__column'>{$value}</td>";
                        }
                    echo "</tr>";
                }
            echo "</table>";
        } else {
            echo "<table class='o-result p-search__result'>";
            echo "<caption class='o-result__caption'>Parent de l'objet recherché</caption>";
            echo "<tr class='o-result__row'><td class ='o-result__column'>Vide</td></tr>";
            echo "</table>";
        }

        echo "<div class='o-result__m-containerResult'>";
        // Search AT
        script_automatic($listResult, $bdd);
        echo "</div>";
        echo "</div>";
    // } else {
    //     echo "<div class='o-alert'>La fonctionnalité Recherche n'accepte que des SELECT et aucune modification de base de données. Vérifiez l'ensemble de vos requêtes pour enlever toutes les potentielles modifications avant de faire une recherche.</div>";
    // }
}





function executeRequests($id, $bdd){
    // All prepare request to do the magic request
    // NC_objects : type objet
    $requete1 = '
    select OBJECT_ID, PARENT_ID, OBJECT_TYPE_ID, NAME, DESCRIPTION 
    from nc_objects 
    where object_id = '.$id.'
    ';
    $prepareRequest1 = $bdd->prepare($requete1);

    // NC_attributes : Caractéristiques principales de l'élément recherché
    $requete2 = '
        select a.NAME, p.ATTR_ID, p.OBJECT_ID, p.VALUE as PARAMS_VALUE, p.DATA, p.LIST_VALUE_ID, l.VALUE as LIST_VALUE, p.DATE_VALUE
        from nc_attributes a, nc_params p, nc_list_values l
        where a.attr_id = p.attr_id and l.list_value_id(+) = p.list_value_id and p.object_id = '.$id.'
        ';
    $prepareRequest2 = $bdd->prepare($requete2);

    // NC_References : Objets asssociés
    $requete3 = '
        select a.NAME as REF_NAME, r. ATTR_ID, r.REFERENCE, o.name as OBJECT_NAME, o2.name OBJECT2_NAME, r.OBJECT_ID
        from nc_references r, nc_objects o, nc_objects o2, nc_attributes a
        where a.attr_id = r.attr_id and r.reference = o.object_id and r.object_id = o2.object_id and r.object_id = '.$id.'
        ';
    $prepareRequest3 = $bdd->prepare($requete3);

    // Références inverses : objets utilisés pour la recherche
    $requete4 = '
        select a.NAME as ATTR_NAME, r. ATTR_ID, r.REFERENCE, o.name as OBJECT_NAME, o2.name as OBJECT2_NAME, r.OBJECT_ID
        from nc_references r, nc_objects o, nc_objects o2, nc_attributes a
        where a.attr_id = r.attr_id and r.reference = o.object_id and r.object_id = o2.object_id and r.reference = '.$id.'
        ';        
    $prepareRequest4 = $bdd->prepare($requete4);

    // Parent de l'objet recherché
    $requete5 = '
        select OBJECT_ID, PARENT_ID, OBJECT_TYPE_ID, NAME, DESCRIPTION 
        from nc_objects 
        where parent_id = '.$id.'
        ';
    $prepareRequest5 = $bdd->prepare($requete5);

    // If all request doesn't update the database, then display the result
    if ((filteredRequestOnlySelect($requete1) &&
    filteredRequestOnlySelect($requete2) &&
    filteredRequestOnlySelect($requete3) &&
    filteredRequestOnlySelect($requete4) &&
    filteredRequestOnlySelect($$requete5)           
    ) == false) {

        // All execute
        $prepareRequest1->execute();
        $prepareRequest2->execute();
        $prepareRequest3->execute();
        $prepareRequest4->execute();
        $prepareRequest5->execute();

        // All fetch
        $result1 = $prepareRequest1->fetchAll(PDO::FETCH_ASSOC);
        $result2 = $prepareRequest2->fetchAll(PDO::FETCH_ASSOC);
        $result3 = $prepareRequest3->fetchAll(PDO::FETCH_ASSOC);
        $result4 = $prepareRequest4->fetchAll(PDO::FETCH_ASSOC);
        $result5 = $prepareRequest5->fetchAll(PDO::FETCH_ASSOC);

        
        // print_r2($result1);
        // print_r2($result2);
        // print_r2($result3);
        // print_r2($result4);
        // print_r2($result5);

        $listResult = [$result1, $result2, $result3, $result4, $result5];

        return $listResult;
    }
}

?>