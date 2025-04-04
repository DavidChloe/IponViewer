<?php

    // Fichier de tests, à mofifier car plus à jour



    // Include
    include "./config/database_RIP.php"; // $bddRIP (ancien fichier, n'existe plus)
    include "./config/database_IPON.php";// $bddIPON (ancien fichier, n'existe plus)
    include "./model/ResultSearch/Column.php";


    if ($connectionDatabase == "IPON") {
        $countIPON = 0;
        $countIPONRIP = 0;

        $requestCountDossier = "
        SELECT count(*)
        FROM nc_params_ix p
        WHERE p.attr_id =8050755945013937881 and p.ix_key = '". $ndVia."'";
        $prepareRequestCountDossier = $bdd->prepare($requestCountDossier);
        $prepareRequestCountDossier->execute();
        $resultCountDossierIPON = $prepareRequestCountDossier->fetchAll(PDO::FETCH_ASSOC);  
        echo "Nb Dossier IPON : ".$resultCountDossierIPON[0]["COUNT(*)"]." | ";
            if ($resultCountDossierIPON[0]["COUNT(*)"] == 1) {
                $countIPON++;
            }

        $requestCountCompte = "
        SELECT count(*)
        FROM nc_params_ix p
        WHERE p.attr_id =7070967307112129805 and p.ix_key = '". $ndVia."'";
        $preparerequestCountCompte = $bdd->prepare($requestCountCompte);
        $preparerequestCountCompte->execute();  
        $resultCountCompteIPON = $preparerequestCountCompte->fetchAll(PDO::FETCH_ASSOC);        
        echo "Nb Compte IPON : ".$resultCountCompteIPON[0]["COUNT(*)"]." | ";
            if ($resultCountCompteIPON[0]["COUNT(*)"] == 1) {
                $countIPON++;
            }

        include "./config/database_RIP.php";

        $requestCountDossier = "
        SELECT count(*)
        FROM nc_params_ix p
        WHERE p.attr_id =8050755945013937881 and p.ix_key = '". $ndVia."'";
        $prepareRequestCountDossier = $bdd->prepare($requestCountDossier);
        $prepareRequestCountDossier->execute();
        $resultCountDossierRIP = $prepareRequestCountDossier->fetchAll(PDO::FETCH_ASSOC);        
        echo "Nb Dossier IPON RIP : ".$resultCountDossierRIP[0]["COUNT(*)"]." | ";
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
        echo "Nb Compte IPON RIP : ".$resultCountCompteRIP[0]["COUNT(*)"];
            if ($resultCountCompteRIP[0]["COUNT(*)"] == 1) {
                $countIPONRIP++;
            }

                echo "<nav class='o_result__a-resultDB'>IPON : ".$countIPON."</nav>";
                echo "<nav class='o_result__a-resultDB'>IPON RIP : ".$countIPONRIP."</nav>";

        include "./config/database_IPON.php";
        //SINON IPON RIP
    } else {
        $countIPON = 0;
        $countIPONRIP = 0;

        $requestCountDossier = "
        SELECT count(*)
        FROM nc_params_ix p
        WHERE p.attr_id =8050755945013937881 and p.ix_key = '". $ndVia."'";
        $prepareRequestCountDossier = $bdd->prepare($requestCountDossier);
        $prepareRequestCountDossier->execute();
        $resultCountDossierRIP = $prepareRequestCountDossier->fetchAll(PDO::FETCH_ASSOC);  
        echo "Nb Dossier IPON RIP : ".$resultCountDossierRIP[0]["COUNT(*)"]." | ";
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
        echo "Nb Compte IPON RIP : ".$resultCountCompteRIP[0]["COUNT(*)"]." | ";
            if ($resultCountCompteRIP[0]["COUNT(*)"] == 1) {
                $countIPONRIP++;
            }

        include "./config/database_IPON.php";

        $requestCountDossier = "
        SELECT count(*)
        FROM nc_params_ix p
        WHERE p.attr_id =8050755945013937881 and p.ix_key = '". $ndVia."'";
        $prepareRequestCountDossier = $bdd->prepare($requestCountDossier);
        $prepareRequestCountDossier->execute();
        $resultCountDossierIPON = $prepareRequestCountDossier->fetchAll(PDO::FETCH_ASSOC);        
        echo "Nb Dossier IPON : ".$resultCountDossierIPON[0]["COUNT(*)"]." | ";
            if ($resultCountDossierIPON[0]["COUNT(*)"] == 1) {
                $countIPON++;
            }

        $requestCountCompte = "
        SELECT count(*)
        FROM nc_params_ix p
        WHERE p.attr_id =7070967307112129805 and p.ix_key = '". $ndVia."'";
        $preparerequestCountCompte = $bdd->prepare($requestCountCompte);
        $preparerequestCountCompte->execute();
        $resultCountCompteIPON = $preparerequestCountCompte->fetchAll(PDO::FETCH_ASSOC);        
        echo "Nb Compte IPON : ".$resultCountCompteIPON[0]["COUNT(*)"];
            if ($resultCountCompteIPON[0]["COUNT(*)"] == 1) {
                $countIPON++;
            }

                echo "<nav class='o_result__a-resultDB'>IPON : ".$countIPON."</nav>";
                echo "<nav class='o_result__a-resultDB'>IPON RIP : ".$countIPONRIP."</nav>";

        include "./config/database_RIP.php";
    }

    ?>