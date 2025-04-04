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

$combinaisonHorsRip = 0;
$combinaisonRip = 0;


if ($connexionIpon[0]) {
    $combinaisonHorsRip +=1;
    $pdoIpon = DatabaseConnectionPool::getPDO('Ipon');    
}

if ($connexionIponRip[0]) {
    $combinaisonRip +=1;
    $pdoIponRip = DatabaseConnectionPool::getPDO('IponRip');    
}


$flag = false;

$id = preg_replace("/[a-zA-Z]/", "", trim($_POST['searchBar']));
$id = str_replace("\n", '', trim($id));


if($id != ""){

    if($combinaisonRip == 0 && $combinaisonHorsRip == 0){
        echo "<div class='o-alert'>Échec de connexion : Vérifier votre connexion Cactus</div>";
    }
    elseif ($combinaisonRip != 0 && $combinaisonHorsRip != 0) {
        $resultReqIpon = executeRequests($id, $pdoIpon);
        $resultReqIponRip = executeRequests($id, $pdoIponRip);
        $flag = true;
    }
    else if ($combinaisonRip == 0 && $combinaisonHorsRip != 0){
        $resultReqIpon = executeRequests($id, $pdoIpon);
        $flag = true;
    }
    elseif ($combinaisonRip != 0 && $combinaisonHorsRip == 0) {
        $resultReqIponRip = executeRequests($id, $pdoIponRip);
        $flag = true;
    }
    else{
        echo "<div class='o-alert'>Erreur Fatale ID1 : Une erreur inattendue est intervenue dans votre recherche par ID. Merci d'en faire par au chargé de développement.</div>";
    }




    // print_r2($resultReqIpon);
    // echo '<br>';
    // print_r2($resultReqIponRip);

    $isEmptyTabIpon = true;
    $isEmptyTabIponRip = true;

    // renvoie false si tab pas vide et true si tableau complètement vide
    if(areArraysEmptyRecursive($resultReqIpon)){
        
        $isEmptyTabIpon = true;
    }
    else{
        $isEmptyTabIpon = false;
    }
    if(areArraysEmptyRecursive($resultReqIponRip)){
        
        $isEmptyTabIponRip = true;
    }
    else{
        $isEmptyTabIponRip = false;
    }


    if($flag){

        if($isEmptyTabIpon && $isEmptyTabIponRip){
            echo "<div class='o-alert'>Aucun résultat n'a été trouvé pour votre ID.</div>";
            saveSearchResultInHistory("Aucun résultat pour cet ID");
        }
        else if(!$isEmptyTabIpon && !$isEmptyTabIponRip){

            echo "<h3 class='o-result__m-dataSearch'><span>Donnée recherchée sur IPON et RIP : </span></br>". $id."</h3>";
            
            
            searchByID($resultReqIpon, $pdoIpon, 'IPON', $id);

            
            echo '<div>--------------------------------------------------------------------</div>';

            
            searchByID($resultReqIponRip, $pdoIponRip, 'RIP', $id);

            saveSearchResultInHistory("Des résultats ont été trouvés dans Ipon et Ipon Rip");
        }
        elseif(!$isEmptyTabIpon && $isEmptyTabIponRip){
            echo "<h3 class='o-result__m-dataSearch'><span>Donnée recherchée sur IPON : </span></br>". $id."</h3>";
            
            searchByID($resultReqIpon, $pdoIpon, 'IPON', $id);

            saveSearchResultInHistory("Des résultats ont été trouvés dans Ipon");
        }
        elseif ($isEmptyTabIpon && !$isEmptyTabIponRip) {
            echo "<h3 class='o-result__m-dataSearch'><span>Donnée recherchée sur RIP : </span></br>". $id."</h3>";
                    
            searchByID($resultReqIponRip, $pdoIponRip, 'RIP', $id);

            saveSearchResultInHistory("Des résultats ont été trouvés dans Ipon Rip");
        }
        else{
            echo "<div class='o-alert'>Erreur Fatale ID2 : Une erreur inattendue est intervenue dans votre recherche par ID. Merci d'en faire par au chargé de développement.</div>";
            
            saveSearchResultInHistory("Erreur Fatale ID2 : Une erreur inattendue est intervenue dans votre recherche par ID.");
        
        }

    }
}
else{
    saveSearchResultInHistory("Format de la chaine incompatible avec le mode de recherche");
    echo "<div class='o-alert'>Format de chaine invalide.</div>";
}


DatabaseConnectionPool::closeConnection('Ipon');
DatabaseConnectionPool::closeConnection('IponRip');



?>