<?php
    /*
    PMO et PMC :
    Présence d’un « point de connexion PM sur bloc opérateur » dans le dossier dans le cas d’un MCIFO (champ : service technique). Solution : vider le champ « point de connexion PM sur bloc opérateur « (en base) ».
    On vérifie que le VIA commence par les trois lettres VIA et que le champs PM est vide.
    Ajouter cette requête à la requête magique :
        SELECT *FROM nc_references 
        WHERE object_id = 9159790253014644645 
        AND attr_id = 8051669976013030099;
    
    Exemple :
        - Conforme : 9147883456913126836
        - Non conforme : 9160503198614444604
    */

    function rule_PMO_PC ($via, array $data) {
        
        $error = [];

        if(!empty($data)){

            $contenu = "";

            // Initialiser le timer
            $time_start = microtime(true);
            

            foreach ($data as $item) {

                foreach ($via as $id) {

                    $array = 0;

                    $checkVIA = str_split($id, 3);
                    if (!empty($item[0])) {

                        //echo 'item[0] pas vide : '.print_r2($item[0]);

                        if ($checkVIA[0]) {

                            
                            // If the the via begin by the three letter VIA then continue the inspection. If not, display an error
                            foreach ($data as $result) {
                                foreach ($result as $result1) {
                                    // if ((in_array("MCIFO Reciproque", $result1) == 1) (in_array("PM Connection Point on Operator Block", $result1) == 1)) {
                                    //     echo "Erreur sur le point de connexion (MCIFO) ;  Solution : vider le champ « point de connexion PM sur bloc opérateur en base via CACTUS ";
                                        //return true;

                                        //print_r2($result1);
                
                                        if (in_array("MCIFO", $result1) == 1) {
                                            $array++;
                                        
                                        }

                                        if (in_array("PM Connection Point on Operator Block", $result1) == 1) {
                                            $array++;
                                        }
                                }
                            }

                            //echo '<br>$array : '.$array;
                            
                            if($array == 2) {
                                $contenu = "<div class='o-rule__m-container o-rule__m-container--red'>
                                <h4 class='o-rule__a-title'>Point de connexion (MCIFO) - ERREUR</h4>
                                <nav class='o-rule__a-more '>Vider le champ « point de connexion PM sur bloc opérateur en base via CACTUS</nav>
                                ";

                                $error[] = "Point de connexion (MCIFO) : ERREUR";

                                break;
                                //return true;
                            } else {
                                $contenu = "<div class='o-rule__m-container o-rule__m-container--green'>
                                <h4 class='o-rule__a-title'>Point de connexion (MCIFO) - CONFORME</h4>
                                <nav class='o-rule__a-more'>Sur le compte et/ou le dossier</nav>
                                ";

                                $error[] = "Point de connexion (MCIFO) : CONFORME";
                                //return false;
                            }
                        } else {
                            $contenu .= "<div class='o-rule__m-container o-rule__m-container--gray'>
                                <h4 class='o-rule__a-title'>Point de connexion (MCIFO) - NON TESTE</h4>
                                <nav class='o-rule__a-more'>Sur le compte et/ou le dossier</nav>
                                ";

                                $error[] = "Point de connexion (MCIFO) : NON TESTE";
                            //return false;
                        }
                    } else {
                        //return false;
                        $error[] = "Point de connexion (MCIFO) : pas de résultat";
                    }
                }
            }

            // Récupération du temps écoulé depuis le lancement du premier timer
            $time_end = microtime(true);
            $time = round(($time_end - $time_start) * 1000, 3);

            $contenu .= "<div class='timer'>Temps de réponse : $time ms</div></div>";

            
            echo $contenu;

            return $error;
            //echo '<hr>';
        }
        else{
            $error[] = "Point de connexion (MCIFO) : NON TESTE";

            return $error;
        }
    }
    
    
?>