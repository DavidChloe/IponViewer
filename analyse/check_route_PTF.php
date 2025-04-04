<?php

// FICHIER APPELE DANS : _base.php

// FONCTION UTILISEE DANS : _base.php

// DESCRIPTION : 

/*  
    Vérifie l'état de complétude de la route PTF dans le compte du client
*/

// ARGUMENTS :

/*  
    $id :
        type : array
        description : contient le numéro de compte client récupéré dans les données du compte et/ou du dossier client
    
    $bdd :
        type : PDO
        description : le PDO associé à la base de données utilisées pour la recherche
    
    $checkCompteDossier : 
        type : entier numérique
        description : établie la présence du compte et du dossier pour le client (1 pour dossier, 2 pour compte, 3 pour compte et dossier)
    
    $ndVia :
        type : chaine de caractère
        description : contient le ND ou le VIA passé dans la barre de recherche par l'utilisateur

*/

// RETOUR : 

/*  
    type : array
*/

function rule_check_route_PTF($id, $bdd, $checkCompteDossier, $ndVia){


    // Si on a un compte
    if($checkCompteDossier != 1){

        // Initialiser le timer
        $time_start = microtime(true);

        // Récupérer le numéro de compte client (dans le dossier ou dans le compte en fonction de la présence de l'un ou de l'autre)
        if($checkCompteDossier == 2 && count($id) == 1){
            $id = $id[0];
        }
        elseif ($checkCompteDossier == 3 && count($id) == 2) {
            $id = $id[1];
        }
        else{
            return ["Route PTF : impossible d'obtenir un résultat"];
        }



        // Récupérer tous les objets associé au numéro de compte client dans NC_References     
        $requete2 = '
        select a.NAME as REF_NAME, r. ATTR_ID, r.REFERENCE, o.name as OBJECT_NAME, o2.name OBJECT2_NAME, r.OBJECT_ID
        from nc_references r, nc_objects o, nc_objects o2, nc_attributes a
        where a.attr_id = r.attr_id and r.reference = o.object_id and r.object_id = o2.object_id and r.object_id = '.$id.'
        ';
        $prepareRequest2 = $bdd->prepare($requete2);
        $prepareRequest2->execute();
        $result2 = $prepareRequest2->fetchAll(PDO::FETCH_ASSOC);

        // Si on a des résultats à la requête
        if(!empty($result2)){

            // Initialiser la variable
            $reference = "";

            // Pour chaque rrésultat de la requête
            foreach ($result2 as $key => $value) {
                
                // Pour chaque valeur de chaque tableaux issues de la requête
                foreach ($value as $k => $v) {
                    // Si la valeur examinée avec pour clé REF_NAME et comme valeur Fiber Route
                    if($k == "REF_NAME" && $v == "Fiber Route"){
                        // Récupérer la valeur du champs REFERENCE
                        $reference = $value["REFERENCE"];
                    }
                }
            }

            // Si la référence n'est pas vide (donc si on a récupérer une valeur précédemment)
            if($reference != ""){

                // Récupérer les données de la route PTF associées à la référence
                $req = "SELECT a.NAME, anls.NAME, p.LIST_VALUE_ID, l.value, v2.value
                FROM nc_attributes a, nc_nls_attributes anls, nc_params p, nc_list_values l, nc_nls_list_values v2
                WHERE a.attr_id = p.attr_id
                AND p.ATTR_ID IN (6041465672013906929,8061162509013299733,9140727527713904375)
                AND anls.attr_id(+) = p.attr_id
                AND l.list_value_id(+) = p.list_value_id
                AND v2.list_value_id(+) = p.list_value_id
                AND p.object_id = '{$reference}'";

                $prepareReq = $bdd->prepare($req);
                $prepareReq->execute();
                $resultReq = $prepareReq->fetchAll(PDO::FETCH_ASSOC);

                // Si on a des résultats
                if(!empty($resultReq)){

                    // Initialiser les variables
                    $content = "";
                    $resume = [];
                    $flag = true;
                    $cadre_vert = "<div class='o-rule__m-container o-rule__m-container--green'>";
                    $cadre_rouge = "<div class='o-rule__m-container o-rule__m-container--red'>";

                    // Pour chaque résultats de la requête
                    foreach ($resultReq as $key => $value) {

                        // En fonction de la valeur du champs NAME, effectué le traitement qui convient pour l'affichage
                        switch($value['NAME']){

                            case 'Statut RPM': 
                                // Pour être conforme, doit avoir comme valeur soit Confirme soit PLP
                                if(in_array($value['VALUE'], array('Confirme', 'PLP'))){
                                    // Initialiser les variables pour l'affichage
                                    $label = "CONFORME";
                                    $resume[] = "".$value['NAME']." : ".$value['VALUE']."";
                                }
                                else{
                                    // Initialiser les variables pour l'affichage
                                    $label = "NON CONFORME";
                                    $resume[] = "".$value['NAME']." : ".$value['VALUE']."";
                                    $flag = false;
                                }
                            ;
                            break;

                            
                            case 'Statut de completude': 
                                // Pour être conforme, doit avoir comme valeur soit Complet soit Partiel
                                if(in_array($value['VALUE'], array('Complet', 'Partiel'))){

                                    if(substr(trim($ndVia), 0, 3) == 'VIA' && $value['VALUE'] == 'Complet'){
                                        // Initialiser les variables pour l'affichage
                                        $label = "NON CONFORME";
                                        $resume[] = "".$value['NAME']." : ".$value['VALUE']."";
                                        $flag = false;
                                    }
                                    else{
                                        // Initialiser les variables pour l'affichage
                                        $label = "CONFORME";
                                        $resume[] = "".$value['NAME']." : ".$value['VALUE']."";
                                    }
                                }
                                else{
                                    // Initialiser les variables pour l'affichage
                                    $label = "NON CONFORME";
                                    $resume[] = "".$value['NAME']." : ".$value['VALUE']."";
                                    $flag = false;
                                }
                            ;
                            break;

                            case 'Etat logique': 
                                // Pour être conforme, doit avoir comme valeur soit Actif soit Equipe
                                if(in_array($value['VALUE'], array('Actif', 'Equipe'))){
                                    // Initialiser les variables pour l'affichage
                                    $label = "CONFORME";
                                    $resume[] = "".$value['NAME']." : ".$value['VALUE']."";
                                }
                                else{
                                    // Initialiser les variables pour l'affichage
                                    $label = "NON CONFORME";
                                    $resume[] = "".$value['NAME']." : ".$value['VALUE']."";
                                    $flag = false;
                                }                                
                            ;
                            break;


                            default :
                            break;

                        }

                        // Compléter le corps de la carte d'information
                        $content .= "<div>
                            <h4 class='o-rule__a-subtitle'>
                                ";
                                if($label == 'CONFORME'){
                                    $content .= '<img class="route_ptf_img" src="public\images\check.png" alt="Documentation Ipon Viewer" title="">';
                                }
                                else{
                                    $content .= '<img class="route_ptf_img" src="public\images\remove.png" alt="Documentation Ipon Viewer" title="">';
                                }
                                $content .= " ".$value['NAME']." - $label
                            </h4>
                            <nav class='o-rule__a-more'>
                                Etat : ".$value['VALUE']."
                            </nav></div>"
                        ;
                    }

                    // Compléter l'affichage de la carte d'information (couleur et contenu)
                    if($flag){
                        $content = $cadre_vert."<h4 class='o-rule__a-title'>ROUTE PTF - CONFORME</h4>".$content;
                    }
                    else{
                        $content = $cadre_rouge."<h4 class='o-rule__a-title'>ROUTE PTF - NON CONFORME</h4>".$content;
                    }

                    // Récupération du temps écoulé depuis le lancement du premier timer
                    $time_end = microtime(true);
                    $time = round(($time_end - $time_start) * 1000, 3);
                
                    $content .= "<div class='timer'>Temps de réponse : $time ms</div></div>"; 

                    // Afficher
                    echo $content;

                    return $resume;
                }
                else{
                    return ["Route PTF : pas de résultat trouvé pour la complétude de la route PTF"];
                }
            }
            else{
                return ["Route PTF : pas de référence trouvée dans le dossier pour la fiber route"];
            }
        }
        else{
            return ["Route PTF : pas de résultat dans le dossier"];
        }
    }
    else{
        return ["Route PTF : pas de résultat"];
    }

}



?>