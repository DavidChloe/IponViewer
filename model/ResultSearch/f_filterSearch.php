<?php

// Filter to get only SELECT request and no update to database
function filteredRequestOnlySelect ($data) {
    $data = explode(" ", $data);
        // If an word associated with a update of the database exists in the request, then stop the execution
        $forbiden = ['UPDATE', 'DELETE', 'MERGE', 'TRUNCATE', 'CREATE', 'DROP', 'OPTIMIZE', 'EXPLAIN', 'INSERT'];
        // For each value in the array
        foreach ($forbiden as $value) {
            foreach ($data as $word) {
            // Look if the value in the array is in the request
            if (strcasecmp($word, $value) == 0 ) {
                // If yes, display an error et return false
                echo "<div class='o-alert'>La fonctionnalité Rechercher ne permet pas de faire des modifications en bases de données. Si vous souhaitez en faire une, sélectionnez l'outil Editer en profil éditeur sinon dirigez vous vers d'autres outils.</div>";
                return true;
            } else {
                // Continue to search
                continue;
            }
        }
        // If a word in a request isn't suitable with the PDO
        $forbiden = ["HAVING"];
        // For each value in the array
        foreach ($forbiden as $value) {
            foreach ($data as $word) {
                // Look if the value in the array is in the request
                if (strcasecmp($word, $value) == 0 ) {
                    // If yes, display an error et return false
                    echo "<div class='o-alert'>The PDO used to safe request sen dto database doesn't allow the word '".$word."'</div>";
                    return true;
                } else {
                    // Continue to search
                    continue;
                }
            }
        }
    }
    // Return true
    return false;
}

// Search in upstream to see if the request get an empty response from the database
//function preliminarySearch (?array $request, $databaseConnection, $nameDatabase) {
function preliminarySearch ($ndVia, $nameDatabase) {
    
    if($nameDatabase == "IPON") {

        $countIPON = 0;
        $countIPONRIP = 0;

        $bdd = DatabaseConnectionPool::getPDO('Ipon');

        $requestCountDossier = "
        SELECT count(*)
        FROM nc_params_ix p
        WHERE p.attr_id =8050755945013937881 and p.ix_key = '". $ndVia."'";
        $prepareRequestCountDossier = $bdd->prepare($requestCountDossier);
        $prepareRequestCountDossier->execute();
        $resultCountDossierIPON = $prepareRequestCountDossier->fetchAll(PDO::FETCH_ASSOC);  
        // echo "Nb Dossier IPON : ".$resultCountDossierIPON[0]["COUNT(*)"]." | ";

        if($resultCountDossierIPON[0]["COUNT(*)"] != "0"){
            echo "<nav class='o-result__a-resultDB'><span class='boldOrangeText'>Dossier IPON : ".$resultCountDossierIPON[0]["COUNT(*)"]."</span></nav>";
        }
        else{
            echo "<nav class='o-result__a-resultDB'>Dossier IPON : ".$resultCountDossierIPON[0]["COUNT(*)"]."</nav>";
        }

        // echo "<nav class='o-result__a-resultDB'>Dossier IPON : ".$resultCountDossierIPON[0]["COUNT(*)"]."</nav>";
            
        

        $requestCountCompte = "
        SELECT count(*)
        FROM nc_params_ix p
        WHERE p.attr_id =7070967307112129805 and p.ix_key = '". $ndVia."'";
        $preparerequestCountCompte = $bdd->prepare($requestCountCompte);
        $preparerequestCountCompte->execute();  
        $resultCountCompteIPON = $preparerequestCountCompte->fetchAll(PDO::FETCH_ASSOC);     


        if($resultCountCompteIPON[0]["COUNT(*)"] != "0"){
            echo "<nav class='o-result__a-resultDB'><span class='boldOrangeText'>Compte IPON : ".$resultCountCompteIPON[0]["COUNT(*)"]."</span></nav>";
        }
        else{
            echo "<nav class='o-result__a-resultDB'>Compte IPON : ".$resultCountCompteIPON[0]["COUNT(*)"]."</nav>";
        }
        //echo "<nav class='o-result__a-resultDB'>Compte IPON : ".$resultCountCompteIPON[0]["COUNT(*)"]."</nav>";
            
        

        //include "./config/database_RIP.php";
        $bdd = DatabaseConnectionPool::getPDO('IponRip');

        $requestCountDossier = "
        SELECT count(*)
        FROM nc_params_ix p
        WHERE p.attr_id =8050755945013937881 and p.ix_key = '". $ndVia."'";
        $prepareRequestCountDossier = $bdd->prepare($requestCountDossier);
        $prepareRequestCountDossier->execute();
        $resultCountDossierRIP = $prepareRequestCountDossier->fetchAll(PDO::FETCH_ASSOC);        
        //echo "Nb Dossier IPON RIP : ".$resultCountDossierRIP[0]["COUNT(*)"]." | ";

        if($resultCountDossierRIP[0]["COUNT(*)"] != "0"){
            echo "<nav class='o-result__a-resultDB'><span class='boldOrangeText'>Dossier RIP : ".$resultCountDossierRIP[0]["COUNT(*)"]."</span></nav>";
        }
        else{
            echo "<nav class='o-result__a-resultDB'>Dossier RIP : ".$resultCountDossierRIP[0]["COUNT(*)"]."</nav>";
        }


        //echo "<nav class='o-result__a-resultDB'>Dossier RIP : ".$resultCountDossierRIP[0]["COUNT(*)"]."</nav>";
            if ($resultCountDossierRIP[0]["COUNT(*)"] == 1) {
                $countIPONRIP++;
            }

        $requestCountCompte = "
        SELECT count(*)
        FROM nc_params_ix p
        WHERE p.attr_id =7070967307112129805 and p.ix_key = '". $ndVia."'";
        $preparerequestCountCompte = $bdd->prepare($requestCountCompte);
        $preparerequestCountCompte->execute();
        $resultCountCompteRIP = $preparerequestCountCompte->fetchAll(PDO::FETCH_ASSOC); 

        if($resultCountCompteRIP[0]["COUNT(*)"] != "0"){
            echo "<nav class='o-result__a-resultDB'><span class='boldOrangeText'>Compte RIP : ".$resultCountCompteRIP[0]["COUNT(*)"]."</span></nav>";
        }
        else{
            echo "<nav class='o-result__a-resultDB'>Compte RIP : ".$resultCountCompteRIP[0]["COUNT(*)"]."</nav>";
        }



        //echo "<nav class='o-result__a-resultDB'>Compte RIP : ".$resultCountCompteRIP[0]["COUNT(*)"]."</nav>";
            // if ($resultCountCompteRIP[0]["COUNT(*)"] == 1) {
            //     $countIPONRIP++;
            // }
                // echo "<nav class='o-result__a-resultDB'>IPON : ".$countIPON."</nav>";
                // echo "<nav class='o-result__a-resultDB'>IPON RIP : ".$countIPONRIP."</nav>";

        //include "./config/database_IPON.php";
        
        //SINON IPON RIP
    } else {
        $countIPON = 0;
        $countIPONRIP = 0;

        //include "./config/database_IPON.php";
        $bdd = DatabaseConnectionPool::getPDO('Ipon');

        $requestCountDossier = "
        SELECT count(*)
        FROM nc_params_ix p
        WHERE p.attr_id =8050755945013937881 and p.ix_key = '". $ndVia."'";
        $prepareRequestCountDossier = $bdd->prepare($requestCountDossier);
        $prepareRequestCountDossier->execute();
        $resultCountDossierIPON = $prepareRequestCountDossier->fetchAll(PDO::FETCH_ASSOC);        
        


        if($resultCountDossierIPON[0]["COUNT(*)"] != 0){
            echo "<nav class='o-result__a-resultDB'><span class='boldOrangeText'>Dossier IPON : ".$resultCountDossierIPON[0]["COUNT(*)"]."</span></nav>";
        }
        else{
            echo "<nav class='o-result__a-resultDB'>Dossier IPON : ".$resultCountDossierIPON[0]["COUNT(*)"]."</nav>";
        }

        //echo "<nav class='o-result__a-resultDB'>Dossier IPON : ".$resultCountDossierIPON[0]["COUNT(*)"]."</nav>";
            

        $requestCountCompte = "
        SELECT count(*)
        FROM nc_params_ix p
        WHERE p.attr_id =7070967307112129805 and p.ix_key = '". $ndVia."'";
        $preparerequestCountCompte = $bdd->prepare($requestCountCompte);
        $preparerequestCountCompte->execute();
        $resultCountCompteIPON = $preparerequestCountCompte->fetchAll(PDO::FETCH_ASSOC);   

        if($resultCountCompteIPON[0]["COUNT(*)"] != 0){
            echo "<nav class='o-result__a-resultDB'><span class='boldOrangeText'>Compte IPON : ".$resultCountCompteIPON[0]["COUNT(*)"]."</span></nav>";
        }
        else{
            echo "<nav class='o-result__a-resultDB'>Compte IPON : ".$resultCountCompteIPON[0]["COUNT(*)"]."</nav>";
        }

        //echo "<nav class='o-result__a-resultDB'>Compte IPON : ".$resultCountCompteIPON[0]["COUNT(*)"]."</nav>";
            

        //include "./config/database_RIP.php";

        $bdd = DatabaseConnectionPool::getPDO('IponRip');
        

        $requestCountDossier = "
        SELECT count(*)
        FROM nc_params_ix p
        WHERE p.attr_id =8050755945013937881 and p.ix_key = '". $ndVia."'";
        $prepareRequestCountDossier = $bdd->prepare($requestCountDossier);
        $prepareRequestCountDossier->execute();
        $resultCountDossierRIP = $prepareRequestCountDossier->fetchAll(PDO::FETCH_ASSOC);  

        if($resultCountDossierRIP[0]["COUNT(*)"] != 0){
            echo "<nav class='o-result__a-resultDB'><span class='boldOrangeText'>Dossier RIP : ".$resultCountDossierRIP[0]["COUNT(*)"]."</span></nav>";
        }
        else{
            echo "<nav class='o-result__a-resultDB'>Dossier RIP : ".$resultCountDossierRIP[0]["COUNT(*)"]."</nav>";
        }

        //echo "<nav class='o-result__a-resultDB'>Dossier RIP : ".$resultCountDossierRIP[0]["COUNT(*)"]."</nav>";
            

        $requestCountCompte = "
        SELECT count(*)
        FROM nc_params_ix p
        WHERE p.attr_id =7070967307112129805 and p.ix_key = '". $ndVia."'";
        $preparerequestCountCompte = $bdd->prepare($requestCountCompte);
        $preparerequestCountCompte->execute();  
        $resultCountCompteRIP = $preparerequestCountCompte->fetchAll(PDO::FETCH_ASSOC);   


        if($resultCountCompteRIP[0]["COUNT(*)"] != 0){
            echo "<nav class='o-result__a-resultDB'><span class='boldOrangeText'>Compte RIP : ".$resultCountCompteRIP[0]["COUNT(*)"]."</span></nav>";
        }
        else{
            echo "<nav class='o-result__a-resultDB'>Compte RIP : ".$resultCountCompteRIP[0]["COUNT(*)"]."</nav>";
        }

        //echo "<nav class='o-result__a-resultDB'>Compte RIP : ".$resultCountCompteRIP[0]["COUNT(*)"]."</nav>";
            

        
    }
}

?>