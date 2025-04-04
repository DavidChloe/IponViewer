<?php
/*
Doublon  AT (ft. Fabien Drieux):
En faisant une recherche sur le nom de l’AT (celui du dossier), deux AT portant le même nom (mais n’ayant pas le même ID (attr_ID), bien sûr) apparaissent : il faut supprimer le « mauvais » AT. 
Vérifier qu’il n’y a pas deux AT sur le même dossier. Si c’est le cas, mettre un point d’alerte. (tableau 4) = DEUX CHAMPS NAME POUR AT DIFFERENTS = ERREUR
    - Soit deux ID identiques mais pas même nom
    - Soit deux noms différents avec deux ID différents sur le même compte

Exemple :
    - 2 AT avec ID differentes et le même nom : VIA01000000173657362 
    - Non conforme RIP pas AT : VIA01000000151629069

*/

function rule_check_AT ($id, array $data, $bdd) {

    if(!empty($data)){

        // Initialiser le timer
        $time_start = microtime(true);

        $at = null;
        // GET AT NAME

        $error = false;
        $contenu = "";

        foreach ($data as $item) {


            // Check if the the data is a repertory number or a client acccount
            // Repertory name
            if (!empty($item[0])) {
                if ($item[0][0]["OBJECT_TYPE_ID"] == "8050748698013937493") {

                    $at = $item[2][1]["OBJECT_NAME"];
                    $explodeNameAt = explode(" ", $at);

                    if($explodeNameAt[0] == "AT") {

                        // HOW MANY AT WITH THE SAME NAME
                        // Check how many AT this information has and their ID
                        $countAT = " select count(*) from nc_objects where name = '".$at."'" ;
                        $prepareRequest = $bdd->prepare($countAT);
                        $prepareRequest->execute();
                        $result = $prepareRequest->fetchAll(PDO::FETCH_ASSOC);
                        if ($result[0]["COUNT(*)"] > 1) {
                            // Get all ID of the AT
                            $allAT = " select OBJECT_ID from nc_objects where name = '".$at."'" ;
                            $prepareRequestAllAT = $bdd->prepare($allAT);
                            $prepareRequestAllAT->execute();
                            $resultAllAT = $prepareRequestAllAT->fetchAll(PDO::FETCH_ASSOC);

                            $error = true;

                            // echo "<div class='o-rule__m-container o-rule__m-container--red'>
                            //         <h4 class='o-rule__a-title'>Vérification AT - ERREUR</h4>
                            //         <nav class='o-rule__a-more '>
                            //         Liste des ID de tous les AT en doublons :
                            //         <ul>";

                            $contenu .=  "<div class='rule_container'><h4 class='o-rule__a-title'>Vérification AT - ERREUR</h4>
                            <nav class='o-rule__a-more '>
                            Liste des ID de tous les AT en doublons :
                            <ul>";

                            foreach ($resultAllAT as $key => $value) {
                                //echo "<li>".$value["OBJECT_ID"]."</li>";

                                $contenu .= "<li>".$value["OBJECT_ID"]."</li>";
                            }
                            // echo "</ul>
                            // </nav>
                            // </div>";

                            $contenu .= "</ul>
                            </nav></div>";


                            //return true;
                        } else {

                            // echo "<div class='o-rule__m-container o-rule__m-container--green'>
                            // <h4 class='o-rule__a-title'>Vérification AT - CONFORME</h4>
                            // <nav class='o-rule__a-more'>AT du dossier unique en base de données</nav>
                            // </div>";

                            $contenu .= "<div class='rule_container'><h4 class='o-rule__a-title'>Vérification AT - CONFORME</h4>
                            <nav class='o-rule__a-more'>AT du dossier unique en base de données</nav></div>";


                            //return false;
                        }
                    } else {

                        $error = true;

                        // echo "<div class='o-rule__m-container o-rule__m-container--red'>
                        //     <h4 class='o-rule__a-title'>Vérification AT - ERREUR</h4>
                        //     <nav class='o-rule__a-more'>Pas d'AT sur le dossier</nav>
                        // </div>";

                        $contenu .= "<div class='rule_container'><h4 class='o-rule__a-title'>Vérification AT - ERREUR</h4>
                        <nav class='o-rule__a-more'>Pas d'AT sur le dossier</nav></div>";

                        //return false;
                    }
                // Client Account
                } elseif ($item[0][0]["OBJECT_TYPE_ID"] == "2091353054013993289") {
                    $idClient = $item[0][0]["OBJECT_ID"];
                    $request = "select r. ATTR_ID, r.REFERENCE, o.name, r.OBJECT_ID
                    from nc_references r, nc_objects o
                    where  r. ATTR_ID = 5092962624013011854 and r.object_id = o.object_id and r.reference =" . $idClient;
                    $prepareRequest = $bdd->prepare($request);
                    $prepareRequest->execute();
                    $result = $prepareRequest->fetchAll(PDO::FETCH_ASSOC);
                    if (!empty($result)) {
                        $at = $result[0]["NAME"];
                        $explodeNameAt = explode(" ", $at);
                        if($explodeNameAt[0] == "AT") {
                            // HOW MANY AT WITH THE SAME NAME
                            // Check how many AT this information has and their ID
                            $countAT = " select count(*) from nc_objects where name = '".$at."'" ;
                            $prepareRequest = $bdd->prepare($countAT);
                            $prepareRequest->execute();
                            $result = $prepareRequest->fetchAll(PDO::FETCH_ASSOC);
                            if ($result[0]["COUNT(*)"] > 1) {
                                // Get all ID of the AT
                                $allAT = " select OBJECT_ID from nc_objects where name = '".$at."'" ;
                                $prepareRequestAllAT = $bdd->prepare($allAT);
                                $prepareRequestAllAT->execute();
                                $resultAllAT = $prepareRequestAllAT->fetchAll(PDO::FETCH_ASSOC);

                                $error = true;

                                // echo "<div class='o-rule__m-container o-rule__m-container--red'>
                                //         <h4 class='o-rule__a-title'>Vérification AT - ERREUR</h4>
                                //         <nav class='o-rule__a-more '>
                                //         Liste des ID de tous les AT en doublons :
                                //         <ul>";


                                $contenu .= "<div class='rule_container'><h4 class='o-rule__a-title'>Vérification AT - ERREUR</h4>
                                <nav class='o-rule__a-more '>
                                Liste des ID de tous les AT en doublons :
                                <ul>";

                                foreach ($resultAllAT as $key => $value) {
                                    //echo "<li>".$value["OBJECT_ID"]."</li>";

                                    $contenu .= "<li>".$value["OBJECT_ID"]."</li>";
                                }


                                // echo "</ul>
                                // </nav>
                                // </div>";


                                $contenu .= "</ul>
                                </nav></div>";

                                //return true;
                            } else {

                                // echo "<div class='o-rule__m-container o-rule__m-container--green'>
                                // <h4 class='o-rule__a-title'>Vérification AT - CONFORME</h4>
                                // <nav class='o-rule__a-more'>AT
                                // du compte client unique en base de données</nav>
                                // </div>";


                                $contenu .= "<div class='rule_container'><h4 class='o-rule__a-title'>Vérification AT - CONFORME</h4>
                                <nav class='o-rule__a-more'>AT du compte client unique en base de données</nav></div>";

                                //return false;
                            }
                        } else {

                            $error = true;

                            // echo "<div class='o-rule__m-container o-rule__m-container--red'>
                            //     <h4 class='o-rule__a-title'>ERREUR : Vérification AT</h4>
                            //     <nav class='o-rule__a-more'>Pas d'AT sur le compte client</nav>
                            // </div>";

                            $contenu .= "<div class='rule_container'><h4 class='o-rule__a-title'>ERREUR : Vérification AT</h4>
                            <nav class='o-rule__a-more'>Pas d'AT sur le compte client</nav></div>";

                            //return false;
                        }
                    } else {

                        $error = true;

                        // echo "<div class='o-rule__m-container o-rule__m-container--red'>
                        //         <h4 class='o-rule__a-title'>Vérification AT - ERREUR</h4>
                        //         <nav class='o-rule__a-more'>Pas d'AT sur le compte client</nav>
                        //     </div>";

                        $contenu .= "<div class='rule_container'><h4 class='o-rule__a-title'>Vérification AT - ERREUR</h4>
                        <nav class='o-rule__a-more'>Pas d'AT sur le compte client</nav></div>";

                        
                        //return false;
                    }
                    // $explodeNameAt = explode(" ", $result)[0]["NAME"];
                    // if (empty($result) || $explodeNameAt != "AT") {
                    // if (empty($result) || $explodeNameAt != "AT") {
                    //     echo "<div class='o-rule__m-container o-rule__m-container--red'>
                    //             <h4 class='o-rule__a-title'>ERREUR : Vérification AT</h4>
                    //             <nav class='o-rule__a-more'>Pas d'AT sur le compte client</nav>
                    //         </div>";
                    //     return false;
                    // }
                    // if ($refNameAt == "AT") {
                    //     $at = $item[2][1]["OBJECT_NAME"];
                    //     print_r2($item[2][1]);
                    //     echo $at;
                    // } else {
                    //     echo "<div class='o-rule__m-container o-rule__m-container--red'>
                    //             <h4 class='o-rule__a-title'>ERREUR : Vérification AT</h4>
                    //             <nav class='o-rule__a-more'>Pas d'AT sur le dossier</nav>
                    //         </div>";
                    //     return false;
                    // }
                // AT FUNCTIONAL POINT
                } elseif ($item[0][0]["OBJECT_TYPE_ID"] == "7070550974112052460") {
                        $at = $item[0][0]["NAME"];
                        $explodeNameAt = explode(" ", $at);
                        if($explodeNameAt[0] == "AT") {
                            // HOW MANY AT WITH THE SAME NAME
                            // Check how many AT this information has and their ID
                            $countAT = " select count(*) from nc_objects where name = '".$at."'" ;
                            $prepareRequest = $bdd->prepare($countAT);
                            $prepareRequest->execute();
                            $result = $prepareRequest->fetchAll(PDO::FETCH_ASSOC);
                            if ($result[0]["COUNT(*)"] > 1) {
                                // Get all ID of the AT
                                $allAT = " select OBJECT_ID from nc_objects where name = '".$at."'" ;
                                $prepareRequestAllAT = $bdd->prepare($allAT);
                                $prepareRequestAllAT->execute();
                                $resultAllAT = $prepareRequestAllAT->fetchAll(PDO::FETCH_ASSOC);

                                $error = true;

                                // echo "<div class='o-rule__m-container o-rule__m-container--red'>
                                //         <h4 class='o-rule__a-title'>Vérification AT - ERREUR</h4>
                                //         <nav class='o-rule__a-more '>
                                //         Liste des ID de tous les AT en doublons :
                                //         <ul>";

                                $contenu .= "<div class='rule_container'><h4 class='o-rule__a-title'>Vérification AT - ERREUR</h4>
                                <nav class='o-rule__a-more '>
                                Liste des ID de tous les AT en doublons :
                                <ul>";


                                foreach ($resultAllAT as $key => $value) {
                                    //echo "<li>".$value["OBJECT_ID"]."</li>";

                                    $contenu .= "<li>".$value["OBJECT_ID"]."</li>";
                                }

                                // echo "</ul>
                                // </nav>
                                // </div>";


                                $contenu .= "</ul>
                                </nav></div>";

                                //return true;

                            } else {
                                // echo "<div class='o-rule__m-container o-rule__m-container--green'>
                                // <h4 class='o-rule__a-title'>Vérification AT - CONFORME</h4>
                                // <nav class='o-rule__a-more'>Point fonctionnel unique en base de données</nav>
                                // </div>";

                                $contenu .= "<div class='rule_container'><h4 class='o-rule__a-title'>Vérification AT - CONFORME</h4>
                                <nav class='o-rule__a-more'>Point fonctionnel unique en base de données</nav></div>";

                                //return false;
                            }
                        } else {

                            $error = true;

                            // echo "<div class='o-rule__m-container o-rule__m-container--red'>
                            //     <h4 class='o-rule__a-title'>Vérification AT - ERREUR</h4>
                            //     <nav class='o-rule__a-more'>Pas d'AT sur ce point fonctionnel</nav>
                            // </div>";

                            $contenu .= "<div class='rule_container'><h4 class='o-rule__a-title'>Vérification AT - ERREUR</h4>
                            <nav class='o-rule__a-more'>Pas d'AT sur ce point fonctionnel</nav></div>";


                            //return false;
                        }            
                } else {

                    $error = true;

                    // echo "<div class='o-rule__m-container o-rule__m-container--gray'>
                    //             <h4 class='o-rule__a-title'>Vérification AT - NON TESTE</h4>
                    //             <nav class='o-rule__a-more '>Pas d'AT testé sur ce type d'objet</nav>
                    //         </div>";

                    $contenu .= "<div class='rule_container'><h4 class='o-rule__a-title'>Vérification AT - NON TESTE</h4>
                    <nav class='o-rule__a-more '>Pas d'AT testé sur ce type d'objet</nav></div>";


                    //return false;
                }
            } else {
                echo "<div class='o-alert'>La requête génère une erreur ou est vide. Vérifier la base de données sur laquelle vous avez effectué la requête ainsi que le type de recherche.</div>";
            }


        }
        
        // Récupération du temps écoulé depuis le lancement du premier timer
        $time_end = microtime(true);
        $time = round(($time_end - $time_start) * 1000, 3);
        
        // Compléter l'affichage
        $timer = "<div class='timer'>Temps de réponse : $time ms</div></div>";
        

        if($error){
            $contenu = "<div class='o-rule__m-container o-rule__m-container--red'>".$contenu.$timer."</div>";
        }
        else{
            $contenu = "<div class='o-rule__m-container o-rule__m-container--green'>".$contenu.$timer."</div>";
        }

        


        echo $contenu;

        return $error;
    }
    else{
        return false;
    }

    // Client account
    // if ($data[0][0]["OBJECT_TYPE_ID"] == "2091353054013993289") {
    //     $idaccount = $data[0][0]["OBJECT_ID"];
    //     $at = $data[3][0]["OBJECT2_NAME"];
    //     // Repertory name
    // } elseif ($data[0][0]["OBJECT_TYPE_ID"] == "8050748698013937493") {
    //     $idND = $data[0][0]["OBJECT_ID"];
    //     $at = $data[2][1]["OBJECT_NAME"];

    //     if (empty($at)) {
    //         echo "<div class='o-rule'>NON TESTE : Vérification AT</div>";
    //         return false;
    //     }
    //     // AT
    // } elseif ($data[0][0]["OBJECT_TYPE_ID"] == "7070550974112052460") {
    //     $at = $data[0][0]["NAME"];
    // } else {
    //     echo "<div class='o-rule'>NON TESTE : Vérification AT</div>";
    //     return false;
    // }
}

?>