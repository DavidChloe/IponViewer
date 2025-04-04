<?php

function script_powerAT ($data, $bdd) {
    
    $listResult = $data;
    // Search AT
    if (!empty($listResult[1])) {
        // For a repertory name
        if ($listResult[0][0]["OBJECT_TYPE_ID"] == "8050748698013937493") {
            // Exemple : 0477371517
            if (!empty($listResult[2][1]["REFERENCE"])) {
                $idAt = $listResult[2][1]["REFERENCE"];
                $request6 = "
                select a.NAME, p.ATTR_ID, p.OBJECT_ID, SUBSTR(p.VALUE, 0, 4000) AS PARAMS_VALUE, p.data, p.LIST_VALUE_ID, l.value, p.DATE_VALUE
                from nc_attributes a, nc_params p, nc_list_values l
                where a.attr_id = p.attr_id and l.list_value_id(+) = p.list_value_id and p.object_id =".$idAt."
                AND p.attr_id =6041442066004855572
                ";
                $prepareRequest6 = $bdd->prepare($request6);
                $prepareRequest6->execute();
                $result6 = $prepareRequest6->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($result6)) {
                    $valuePower = explode("\n", $result6[0]["PARAMS_VALUE"]);
                    //Search Keyword : "Puissance :"
                    $searchwordP = "Puissance";
                    $valuePowerP = array_filter($valuePower, function($var) use ($searchwordP) { return preg_match("/\b$searchwordP\b/i", $var); });
                    if (!empty(array_keys($valuePowerP))) {                            
                    // if (in_array($searchwordS,$valuePower)  != false) {
                    // Send power
                    $keyPositionP = array_keys($valuePowerP)[0];
                    $valuePowerP = explode(":", $valuePowerP[$keyPositionP]);  
                    $searchwordS = "Puissance emise";
                    $valuePowerS = array_filter($valuePower, function($var) use ($searchwordS) { return preg_match("/\b$searchwordS\b/i", $var); });
                        if (!empty(array_keys($valuePowerS))) {
                            $keyPositionS = array_keys($valuePowerS)[0];
                            $valuePowerS = explode(":", $valuePowerS[$keyPositionS]);
                            // Receive power
                            $searchwordR = "Puissance recue";
                            $valuePowerR = array_filter($valuePower, function($var) use ($searchwordR) { return preg_match("/\b$searchwordR\b/i", $var); });
                            if (!empty(array_keys($valuePowerR))) {
                                $keyPositionR = array_keys($valuePowerR)[0];
                                $valuePowerR = explode(":", $valuePowerR[$keyPositionR]);
                                echo "<table class='o-result p-search__result'>";
                                echo "<caption class='o-result__caption'>Infos équipement de l'AT</caption>";
                                echo "<th class ='o-result__header'>{$valuePowerS[0]}</th>";
                                echo "<th class ='o-result__header'>{$valuePowerR[0]}</th>";
                                echo "<tr class='o-result__row'>";            
                                echo "<td class='o-result__column'>{$valuePowerS[1]}</td>";  
                                echo "<td class='o-result__column'>{$valuePowerR[1]}</td>";         
                                echo "</tr>";
                                echo "</table>";
                            } else {
                                echo "<table class='o-result p-search__result'>";
                                echo "<caption class='o-result__caption'>Infos équipement de l'AT</caption>";
                                echo "<tr class='o-result__row'><td class ='o-result__column'>Puissances absentes</td></tr>";
                                echo "</table>";
                            }
                        } else {
                            echo "<table class='o-result p-search__result'>";
                            echo "<caption class='o-result__caption'>Infos équipement de l'AT</caption>";
                            echo "<tr class='o-result__row'><td class ='o-result__column'>Puissances absentes</td></tr>";
                            echo "</table>";
                        }
                    } else {
                        echo "<table class='o-result p-search__result'>";
                        echo "<caption class='o-result__caption'>Infos équipement de l'AT</caption>";
                        echo "<tr class='o-result__row'><td class ='o-result__column'>Puissances absentes</td></tr>";
                        echo "</table>";
                    }
                } else {
                    echo "<table class='o-result p-search__result'>";
                    echo "<caption class='o-result__caption'>Infos équipement de l'AT</caption>";
                    echo "<tr class='o-result__row'><td class ='o-result__column'>Puissances absentes</td></tr>";
                    echo "</table>";
                }
            }
        // For a client account
        } elseif ($listResult[0][0]["OBJECT_TYPE_ID"] == "2091353054013993289"){
            // Exemple : 0477371517
            if (!empty($listResult[3][0]["OBJECT_ID"])) {
                $idAt = $listResult[3][0]["OBJECT_ID"];
                $request6 = "
                select a.NAME, p.ATTR_ID, p.OBJECT_ID, SUBSTR(p.VALUE, 0, 4000) AS PARAMS_VALUE, p.data, p.LIST_VALUE_ID, l.value, p.DATE_VALUE
                from nc_attributes a, nc_params p, nc_list_values l
                where a.attr_id = p.attr_id and l.list_value_id(+) = p.list_value_id and p.object_id =".$idAt."
                AND p.attr_id = 6041442066004855572
                ";
                $prepareRequest6 = $bdd->prepare($request6);
                $prepareRequest6->execute();
                $result6 = $prepareRequest6->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($result6)) {
                    $valuePower = explode("\n", $result6[0]["PARAMS_VALUE"]);
                    //Search Keyword : "Puissance :"
                    $searchwordP = "Puissance";
                    $valuePowerP = array_filter($valuePower, function($var) use ($searchwordP) { return preg_match("/\b$searchwordP\b/i", $var); });  
                    if (!empty(array_keys($valuePowerP))) {                            
                    // if (in_array($searchwordS,$valuePower)  != false) {
                    // Send power
                        $keyPositionP = array_keys($valuePowerP)[0];
                        $valuePowerP = explode(":", $valuePowerP[$keyPositionP]);
                        $searchwordS = "Puissance emise";
                        $valuePowerS = array_filter($valuePower, function($var) use ($searchwordS) { return preg_match("/\b$searchwordS\b/i", $var); });
                        if (!empty(array_keys($valuePowerS))) {                            
                            $keyPositionS = array_keys($valuePowerS)[0];
                            $valuePowerS = explode(":", $valuePowerS[$keyPositionS]);                        
                            // Receive power
                            $searchwordR = "Puissance recue";
                            $valuePowerR = array_filter($valuePower, function($var) use ($searchwordR) { return preg_match("/\b$searchwordR\b/i", $var); });
                            if (!empty(array_keys($valuePowerR))) {
                                $keyPositionR = array_keys($valuePowerR)[0];
                                $valuePowerR = explode(":", $valuePowerR[$keyPositionR]);
                                echo "<table class='o-result p-search__result'>";
                                echo "<caption class='o-result__caption'>Infos équipement de l'AT</caption>";
                                echo "<th class ='o-result__header'>{$valuePowerS[0]}</th>";
                                echo "<th class ='o-result__header'>{$valuePowerR[0]}</th>";
                                echo "<tr class='o-result__row'>";            
                                echo "<td class='o-result__column'>{$valuePowerS[1]}</td>";  
                                echo "<td class='o-result__column'>{$valuePowerR[1]}</td>";         
                                echo "</tr>";
                                echo "</table>";
                            } else {
                                echo "<table class='o-result p-search__result'>";
                                echo "<caption class='o-result__caption'>Infos équipement de l'AT</caption>";
                                echo "<tr class='o-result__row'><td class ='o-result__column'>Puissances absentes</td></tr>";
                                echo "</table>";
                            }
                        } else {
                            echo "<table class='o-result p-search__result'>";
                            echo "<caption class='o-result__caption'>Infos équipement de l'AT</caption>";
                            echo "<tr class='o-result__row'><td class ='o-result__column'>Puissances absentes</td></tr>";
                            echo "</table>";
                        }
                    } else {
                        echo "<table class='o-result p-search__result'>";
                        echo "<caption class='o-result__caption'>Infos équipement de l'AT</caption>";
                        echo "<tr class='o-result__row'><td class ='o-result__column'>Puissances absentes</td></tr>";
                        echo "</table>";
                    }
                } else {
                    echo "<table class='o-result p-search__result'>";
                    echo "<caption class='o-result__caption'>Infos équipement de l'AT</caption>";
                    echo "<tr class='o-result__row'><td class ='o-result__column'>Puissances absentes</td></tr>";
                    echo "</table>";
                }
            } else {
                echo "<table class='o-result p-search__result'>";
                echo "<caption class='o-result__caption'>Infos équipement de l'AT</caption>";
                echo "<tr class='o-result__row'><td class ='o-result__column'>Puissances absentes</td></tr>";
                echo "</table>";
            }
        // For AT functional point
        // Exemple : VIA01000000119393908
        } 
        elseif ($listResult[0][0]["OBJECT_TYPE_ID"] == "7070550974112052460") {
            if (!empty($listResult[1][5]["PARAMS_VALUE"])) {
                $valuePower = $listResult[1][5]["PARAMS_VALUE"];
                // Send power
                $searchwordS = "Puissance emise";
                if (in_array($searchwordS, explode("\n",$valuePower))  != false) {
                    $valuePowerS = array_filter($valuePower, function($var) use ($searchwordS) { return preg_match("/\b$searchwordS\b/i", $var); });
                    if (!empty(array_keys($valuePowerS))) {
                        $keyPositionS = array_keys($valuePowerS)[0];
                        $valuePowerS = explode(":", $valuePowerS[$keyPositionS]);
                        // Receive power
                        $searchwordR = "Puissance recue";
                        $valuePowerR = array_filter($valuePower, function($var) use ($searchwordR) { return preg_match("/\b$searchwordR\b/i", $var); });
                        if (!empty(array_keys($valuePowerR))) {
                            $keyPositionR = array_keys($valuePowerR)[0];
                            $valuePowerR = explode(":", $valuePowerR[$keyPositionR]);
                            echo "<table class='o-result p-search__result'>";
                            echo "<caption class='o-result__caption'>Infos équipement de l'AT</caption>";
                            echo "<th class ='o-result__header'>{$valuePowerS[0]}</th>";
                            echo "<th class ='o-result__header'>{$valuePowerR[0]}</th>";
                            echo "<tr class='o-result__row'>";            
                            echo "<td class='o-result__column'>{$valuePowerS[1]}</td>";  
                            echo "<td class='o-result__column'>{$valuePowerR[1]}</td>";         
                            echo "</tr>";
                            echo "</table>";
                        } else {
                            echo "<table class='o-result p-search__result'>";
                            echo "<caption class='o-result__caption'>Infos équipement de l'AT</caption>";
                            echo "<tr class='o-result__row'><td class ='o-result__column'>Puissances absentes</td></tr>";
                            echo "</table>";
                        }
                    } else {
                        echo "<table class='o-result p-search__result'>";
                        echo "<caption class='o-result__caption'>Infos équipement de l'AT</caption>";
                        echo "<tr class='o-result__row'><td class ='o-result__column'>Puissances absentes</td></tr>";
                        echo "</table>";
                    }
                } else {
                    echo "<table class='o-result p-search__result'>";
                    echo "<caption class='o-result__caption'>Infos équipement de l'AT</caption>";
                    echo "<tr class='o-result__row'><td class ='o-result__column'>Puissances absentes</td></tr>";
                    echo "</table>";
                }
            } else {
                echo "<table class='o-result p-search__result'>";
                echo "<caption class='o-result__caption'>Infos équipement de l'AT</caption>";
                echo "<tr class='o-result__row'><td class ='o-result__column'>Puissances absentes</td></tr>";
                echo "</table>";
            }
        } else {
            echo "<table class='o-result p-search__result'>";
            echo "<caption class='o-result__caption'>Infos équipement de l'AT</caption>";
            echo "<tr class='o-result__row'><td class ='o-result__column'>Puissances absentes</td></tr>";
            echo "</table>";
        }
    } else {
        echo "<table class='o-result p-search__result'>";
        echo "<caption class='o-result__caption'>Infos équipement de l'AT</caption>";
        echo "<tr class='o-result__row'><td class ='o-result__column'>Puissances absentes</td></tr>";
        echo "</table>";
    }
}

?>