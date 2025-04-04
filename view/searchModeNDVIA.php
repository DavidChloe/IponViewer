<?php


    $connexionIpon = DatabaseConnectionPool::createConnection('Ipon');
    $connexionIponRip = DatabaseConnectionPool::createConnection('IponRip');



    // Renvoyer la réponse sous forme de JSON pour être utiliser dans le javascript
    $tabDatabase = ["connexionIpon"=>$connexionIpon[0],"connexionIponRip"=>$connexionIponRip[0]];

    $tabHeureTest = ["heureTestIpon" => $connexionIpon[1], "heureTestIponRip" => $connexionIponRip[1]];

    $tabDelais = ["delaisConnexionIpon"=>$connexionIpon[2],"delaisConnexionIponRip"=>$connexionIponRip[2]];

    $jsonTabDatabase = json_encode($tabDatabase);
    $jsonTabHeureTest = json_encode($tabHeureTest);
    $jsonTabDelais = json_encode($tabDelais);

?>

<script>

// Récupérer le tableau php dans le javascript
let tab = <?php echo $jsonTabDatabase; ?>;
let tabHeure = <?php echo $jsonTabHeureTest; ?>;
let tabDelais = <?php echo $jsonTabDelais; ?>;
 
// Si notre tableau n'est pas vide
if(Object.keys(tab).length != 0){

    for (const key in tab) {

        updateLocalStorage(key, tab[key]);
        
    }
}


if(Object.keys(tabHeure).length != 0){

    for (const key in tabHeure) {

        updateLocalStorage(key, tabHeure[key]);
        
    }
}

if(Object.keys(tabDelais).length != 0){

    for (const key in tabDelais) {

        updateLocalStorage(key, tabDelais[key]);

    }
}

</script>

<?php

    // Si aucune connexion aux base de données Ipon et Ipon Rip n'a pu être établie
    if(!$connexionIpon[0] && !$connexionIponRip[0]){
        // Renvoyer un message d'erreur
        echo "<div class='o-alert'>Recherche avortée car la connexion à vos bases de données est impossible.</div>";
    }
    else{
        
        // ND : 0477371517 
        // VIA: VIA01000000119393908
        $ndVia = preg_replace("/[^A-Za-z0-9\n\r]/", "", trim($_POST['searchBar']));
        $ndVia = str_replace("\n", '', $ndVia);
        $id = 0;


        $resultClientHorsRip = [];
        $resultNDHorsRip = [];
        $resultClientRip = [];
        $resultNDRip = [];


        

        
        if($connexionIpon[0]){
            // Check if the data has a client and/or a repertory number
            // Ré-aiguillage vers la bonne base de données 
            //On effectue d'abord un test sur la bdd IPON, si on trouve un dossier ou un compte client, alors c'est qu'on est sur la bonne bdd, sinon, on fait un test sur l'autre bdd
            //$connectionDatabase = "IPON";
            
            // Récupération du PDO pour les requêtes dans Ipon
            $bdd = DatabaseConnectionPool::getPDO('Ipon');

            // Client account
            $requestClient = "
                SELECT p.attr_id, p.object_id, p.ix_key
                FROM nc_params_ix p
                WHERE p.attr_id = 7070967307112129805 and p.ix_key = '". $ndVia."'";
            $prepareRequestClient = $bdd->prepare($requestClient);
            $prepareRequestClient->execute();
            $resultClientHorsRip = $prepareRequestClient->fetchAll(PDO::FETCH_ASSOC);

            // print_r2($resultClientHorsRip);

            // Repertory number
            $requestND = "
                SELECT p.attr_id, p.object_id, p.ix_key
                FROM nc_params_ix p
                WHERE p.attr_id = 8050755945013937881 and p.ix_key = '". $ndVia."'";
            $prepareRequestND = $bdd->prepare($requestND);
            $prepareRequestND->execute();
            $resultNDHorsRip = $prepareRequestND->fetchAll(PDO::FETCH_ASSOC);

            
        }

        if($connexionIponRip[0]){

            // On change la valeur de $connectionDatabase
            //$connectionDatabase = "RIP";

            $bdd = DatabaseConnectionPool::getPDO('IponRip');

            // Client account
            $requestClient = "
                SELECT p.attr_id, p.object_id, p.ix_key
                FROM nc_params_ix p
                WHERE p.attr_id = 7070967307112129805 and p.ix_key = '". $ndVia."'";
            $prepareRequestClient = $bdd->prepare($requestClient);
            $prepareRequestClient->execute();
            $resultClientRip = $prepareRequestClient->fetchAll(PDO::FETCH_ASSOC);

            // Repertory number
            $requestND = "
                SELECT p.attr_id, p.object_id, p.ix_key
                FROM nc_params_ix p
                WHERE p.attr_id = 8050755945013937881 and p.ix_key = '". $ndVia."'";
            $prepareRequestND = $bdd->prepare($requestND);
            $prepareRequestND->execute();
            $resultNDRip = $prepareRequestND->fetchAll(PDO::FETCH_ASSOC);
            

        }


        if(!empty($resultClientHorsRip)){
            $resultClient = $resultClientHorsRip;
        }
        elseif(!empty($resultClientRip)){
            $resultClient = $resultClientRip;
        }
        else{
            $resultClient = [];
        }

        if(!empty($resultNDHorsRip)){
            $resultND = $resultNDHorsRip;
        }
        elseif(!empty($resultNDRip)){
            $resultND = $resultNDRip;
        }
        else{
            $resultND = [];
        }


        if(count($resultClientHorsRip) >= 1 || count($resultNDHorsRip) >= 1){

            $connectionDatabase = "IPON";

            $bdd = $connexionIpon[3];

            // On change la valeur de 'radioChecked' dans le localStorage
            ?>
            <script>
                const btnRadioIPON = document.getElementsByName("database");
                btnRadioIPON[0].checked = true;
                localStorage.setItem('radioChecked', "IPON");
                console.log("Changement du bouton radio : en IPON");
            </script>
            <?php
        }
        elseif(count($resultClientRip) >= 1 || count($resultNDRip) >= 1){

            $connectionDatabase = "RIP";

            $bdd = $connexionIponRip[3];

            ?>
            <script>
                const btnRadioRIP = document.getElementsByName("database");
                btnRadioRIP[1].checked = true;
                localStorage.setItem('radioChecked', "RIP");
                console.log("Changement du bouton radio : en RIP");
            </script>
            <?php
        }
        else{
            ?>
            <script>
                const btnRadioRIP = document.getElementsByName("database");
                if(localStorage.getItem('radioChecked') == "RIP"){
                    localStorage.setItem('radioChecked', "RIP");
                    btnRadioRIP[1].checked = true;
                }
                else if(localStorage.getItem('radioChecked') == "IPON"){
                    localStorage.setItem('radioChecked', "IPON");
                    btnRadioRIP[0].checked = true;
                }
                else{
                    localStorage.setItem('radioChecked', "IPON");
                    btnRadioRIP[0].checked = true;
                }
            </script>
            <?php
        }
        


        $checkCompteDossier = 0;
        if(!empty($resultClient)){
            $checkCompteDossier += 1; 
        }
        if(!empty($resultND)){
            $checkCompteDossier += 2; 
        }






        if(empty($resultClient) && empty($resultND)){
            echo "<div class='o-alert'>Aucune données n'a été trouvée. Vérifier l'accès à vos bases de données ou le format des données entrées.</div>";

            saveSearchResultInHistory("Aucune données n'a été trouvée.");
        }
        else{

            echo "<h3 class='o-result__m-dataSearch'><span>Donnée recherchée sur ".$connectionDatabase." : </span></br>". $ndVia."</h3>";

            

            // Check if there is a result in databases
            // TODO : Ajouter pour requestClient + système incrémentation pour ND IPON, ND RIP, CLient account IPON & Client account RIP + feat Fabien
            preliminarySearch($ndVia, $connectionDatabase);


            // If there is only a client account
            if (!empty($resultClient) && empty($resultND)) {
                $id = $resultClient[0]["OBJECT_ID"];
                // Check if there is a result in databases
                echo "<div class='o-result__m-typeContainer'>
                <button class='o-result__a-resultType'>Compte Client</button>
                </div>";


                $listResult = executeRequests($id, $bdd);

                $refAT = $listResult[3][0]["OBJECT2_NAME"];

                

                if($refAT == "" || $refAT == null){
                    $refAT = "";
                }
                
               

                rule_automatic([$id], [$listResult], $bdd, $connectionDatabase, $ndVia, $checkCompteDossier, $refAT);
                

                searchByID($listResult, $bdd, $connectionDatabase, $ndVia);

                //saveSearchResultInHistory("");
                
                
            } // If there is only a repertory number
            elseif (!empty($resultND) && empty($resultClient)) {
                $id = $resultND[0]["OBJECT_ID"];
                // à faire : Afficher un message qui dis juste un ND
                echo "<div class='o-result__m-typeContainer'>
                <button class='o-result__a-resultType'>Dossier</button>
                </div>";


                $listResult = executeRequests($id, $bdd);

                $refAT = $listResult[2][1]["OBJECT_NAME"];

                if($refAT == "" || $refAT == null){
                    $refAT = "";
                }

                rule_automatic([$id], [$listResult], $bdd, $connectionDatabase, $ndVia, $checkCompteDossier, $refAT);

                searchByID($listResult, $bdd, $connectionDatabase, $ndVia);

                //saveSearchResultInHistory("");

                // If there is a client account and an ND
            } 
            elseif (!empty($resultClient) && !empty($resultND)) {

                

                // print_r2($resultClient);
                // print_r2($resultND);
                // echo $connectionDatabase;
                // echo "<br>".$ndVia;


                // Faire deux boutons avec la valeur de ID en value et l'utilisateur click sur celui qu'il veut
                echo "<div class='o-result__m-typeContainer'>
                <button class='o-result__a-resultType o-result__a-typeClient' value='".$resultClient[0]["OBJECT_ID"]."'>Compte Client</button>
                <button class='o-result__a-resultType o-result__a-typeND' value='".$resultND[0]["OBJECT_ID"]."'>Dossier</button>
                </div>";


                $idCompteClient = $resultClient[0]["OBJECT_ID"];            
                $idDossierClient = $resultND[0]["OBJECT_ID"];

                
                $listResult1 = executeRequests($idCompteClient, $bdd);
                $listResult2 = executeRequests($idDossierClient, $bdd);
                
                $refAT = $listResult1[3][0]["OBJECT2_NAME"];
                
                if($refAT == "" || $refAT == null){

                    $refAT = $listResult2[2][1]["OBJECT_NAME"];

                    if($refAT == "" || $refAT == null){
                        $refAT = "";
                    }
                }

                rule_automatic([$idCompteClient, $idDossierClient], [$listResult1, $listResult2], $bdd, $connectionDatabase, $ndVia, $checkCompteDossier, $refAT);



                // Display result, a javascript script will toggle the result when the user select the information he want to display
                echo "<div class='o-result__m-containerClient hidden'>";
                searchByID($listResult1, $bdd, $connectionDatabase, $ndVia);
                echo "</div>";


                echo "<div class='o-result__m-containerND hidden'>";
                searchByID($listResult2, $bdd, $connectionDatabase,$ndVia);
                echo "</div>";



                //saveSearchResultInHistory("");
            }
                else {
                echo "<div class='o-alert'>La requête génère une erreur ou est vide. Vérifier la base de données sur laquelle vous avez effectué la requête ainsi que le type de recherche.</div>";

                saveSearchResultInHistory("Aucune données n'a été trouvée.");
            }
        }

    }

    DatabaseConnectionPool::closeConnection('Ipon');
    DatabaseConnectionPool::closeConnection('IponRip');

?>