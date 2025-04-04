
<?php

$databaseSelected = $_POST["database"];

$inputSearch = trim($_POST['searchBar']);

$GER = formateStringForRequest($databaseSelected, $inputSearch);



//La taille minimale de la chaîne d'un id est 19, si elle n'est pas respectée, on affiche un message d'erreur
if(strlen($GER) > 18){

    //si que lettre : sortie = 0
    //si que chiffre ou chiffre et lettre : sortie = les chiffres

    // On traite la chaine comme si elle était faite d'id IPON non RIP 
    $ndVia = formateStringForRequest("", $inputSearch);

    
    $resultReqGER = [];
    $resultReqIPON = [];

    if($_POST['database'] == 'RIP'){


        $connexionIponRip = DatabaseConnectionPool::createConnection('IponRip');
        $connexionGeoresoRip = DatabaseConnectionPool::createConnection('GeoresoRip');

        // Renvoyer la réponse sous forme de JSON pour être utiliser dans le javascript
        $tabDatabase = ["connexionIponRip"=>$connexionIponRip[0], "connexionGeoresoRip"=>$connexionGeoresoRip[0]];

        $tabHeureTest = ["heureTestIponRip" => $connexionIponRip[1], "heureTestGeoresoRip" => $connexionGeoresoRip[1]];

        $tabDelais = ["delaisConnexionIponRip"=>$connexionIponRip[2],"delaisConnexionGeoresoRip"=>$connexionGeoresoRip[2]];
    

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

        if ($connexionIponRip[0]) {

            $bdd = DatabaseConnectionPool::getPDO('IponRip');

            $connectionDatabase = "RIP";

            $combinaisonRip +=1;

            $requestPT = requestForIPON($ndVia);
            $prepareRequestPT = $bdd->prepare($requestPT);
            $prepareRequestPT->execute();
            $resultReqIPON = $prepareRequestPT->fetchAll(PDO::FETCH_ASSOC);

            DatabaseConnectionPool::closeConnection('IponRip');
        }

        if ($connexionGeoresoRip[0]) {
            
            $pdo = DatabaseConnectionPool::getPDO('GeoresoRip');

            $connectionDatabase = "RIP";

            $combinaisonRip += 2;
            
            $test = requestForGER($GER);
            $prep = $pdo-> prepare($test);
            $prep -> execute();
            $resultReqGER = $prep ->fetchAll(PDO::FETCH_ASSOC);
            
            DatabaseConnectionPool::closeConnection('GeoresoRip');
        }

    }
        

    if($_POST['database'] == 'IPON'){

        $connexionIpon = DatabaseConnectionPool::createConnection('Ipon');
        $connexionGeoreso = DatabaseConnectionPool::createConnection('Georeso');

        // Renvoyer la réponse sous forme de JSON pour être utiliser dans le javascript
        $tabDatabase = ["connexionIpon"=>$connexionIpon[0], "connexionGeoreso" =>$connexionGeoreso[0]];

        $tabHeureTest = ["heureTestIpon" => $connexionIpon[1], "heureTestGeoreso" => $connexionGeoreso[1]];

        $tabDelais = ["delaisConnexionIpon"=>$connexionIpon[2],"delaisConnexionGeoreso" =>$connexionGeoreso[2]];


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

        if ($connexionIpon[0]) {

            $bdd = DatabaseConnectionPool::getPDO('Ipon');

            $connectionDatabase = "IPON";

            $combinaisonHorsRip +=1;

            $requestPT = requestForIPON($ndVia);
            $prepareRequestPT = $bdd->prepare($requestPT);
            $prepareRequestPT->execute();
            $resultReqIPON = $prepareRequestPT->fetchAll(PDO::FETCH_ASSOC);

            DatabaseConnectionPool::closeConnection('Ipon');
        }

        if ($connexionGeoreso[0]) {

            $pdo = DatabaseConnectionPool::getPDO('Georeso');

            $connectionDatabase = "IPON";

            $combinaisonHorsRip += 2;
            
            $test = requestForGER($GER);
            $prep = $pdo-> prepare($test);
            $prep -> execute();
            $resultReqGER = $prep ->fetchAll(PDO::FETCH_ASSOC);

            DatabaseConnectionPool::closeConnection('Georeso');
        }

    }
        

    $tailleResReqGER = count($resultReqGER);
    $tailleResReqIPON = count($resultReqIPON);

    if(empty($resultReqIPON) && empty($resultReqGER)){  
        if($_POST['database'] == 'IPON' && $combinaisonHorsRip === 1){
            echo "<div class='o-alert'>Aucune correspondance trouvée pour votre ID dans la base de données IPON.</div>";
            saveSearchResultInHistory("Aucune correspondance trouvée dans base de données IPON.");
        } 
        elseif($_POST['database'] == 'RIP' && $combinaisonRip === 1){
            echo "<div class='o-alert'>Aucune correspondance trouvée pour votre ID dans la base de données IPON RIP.</div>";
            saveSearchResultInHistory("Aucune correspondance trouvée dans base de données IPON RIP.");
        }         
        else{
            echo "<div class='o-alert'>Les données saisies sont incorrectes ou la base de données sélectionnée n'est pas la bonne.</div>";
            saveSearchResultInHistory("Donnée(s) saisie(s) incorrecte(s) ou la base de données sélectionnée n'est pas la bonne.");
        }     
        
    }
    else{

        $titre = "<h2 class='titre2'>RÉSULTATS VUS DE GEORESO : </h2>";

        // On affiche le selecteur pour le nombre de ligne dans le tableau que s'il y a plus de 5 résultats (5 étant le nombre de ligne minimale que l'on veuille afficher)
        if($tailleResReqGER > 5 || $tailleResReqIPON > 5){
            //affichage du menu déroulant pour le choix du nombre de ligne à afficher
            $selecteurLigne = "<div class='wrapper_insider'> 
                    <label for='row_select'> Afficher : </label>
                    <select name='row_select' id='row_select'>
                        <option value='5'>5</option>
                        <option value='10'>10</option>
                        <option value='15' selected='selected'>15</option>
                        <option value='20'>20</option>
                        <option value='30'>30</option>";
            if($tailleResReqGER > 30){ 
                $selecteurLigne .= "<option value='".$tailleResReqGER."'>".$tailleResReqGER."</option>.";
            }
            $selecteurLigne .= "</select><div> lignes</div> </div>";           
        }


        // $resultReqGER -> les objets de Géoreso (colonne de droite du tableau final)
        // $resultReqIPON -> les objets de IPON (colonne de gauche du tableau final)

        // En-tête du tableau d'affichage
        $enteteTableau = 
            "<div class='p-search__result2'>
            <table class='o-result p-search__result2 pagination' data-max-page='15'>
                <tr class='o-result__row__title'>
                        <th class='o-result__column'>Ligne</th>
                        <th class='o-result__column'>ID IPON</th>
                        <th class='o-result__column'>PT</th>
                        <th class='o-result__column'>Site Support IPON</th>     
                        <th class='o-result__column'></th> 
                        <th class='o-result__column'>PT GER</th> 
                        <th class='o-result__column'>Site support GER</th> 
                        <th class='o-result__column'>Type site</th> 
                        <th class='o-result__column'>Commentaire</th>           
                </tr>
            "
        ;

        $piedTableau = "</table></div>";


        // Affichage du nombre de ligne
        if($tailleResReqGER < 5){
            $finDePage = "
            <div class='pagination-info'>
                <p class='ligne'></p>
            </div>";
        }// ou affichage de la navigation 
        else {
            $finDePage = "
            <div class='pagination-info'>
                <span class='pagination-btn prev'>Page précédente</span>
                <p class='pagination-page'>0/0</p>
                <p class='ligne'></p>
                
                <div class='wrapper_insider'>
                    <input type='number' name='numPageChoice' id='numPageChoice' value='1' min='1' step='1'>
                    <label for='numPageChoice' id='labelPageChoice'></label>
                </div>

                <span class='pagination-btn next'>Page suivante</span>
            </div>";
        }



        // Initialisation de la numérotation des case du tableau
        $index = 0;


        // Si j'ai uniquement des résultats dans le tableau contenant les informations venues d'IPON alors je n'affiche que ceux là dans mon tableau
        if($tailleResReqGER === 0 && $tailleResReqIPON !== 0 && ($combinaisonHorsRip !== 3 && $combinaisonRip !== 3)){
            for($i = 0; $i < $tailleResReqIPON; $i++){
                $index++;
                $corpsTableau .= createLineTab($index, $resultReqIPON[$i]['OBJECT_ID'], $resultReqIPON[$i]['NUM_PT'], $resultReqIPON[$i]['SITE'], "", "", "", "", "");                    
            }


            $resume = createResume($index, 0, 0, $index);

            
            saveSearchResultInHistory(" $index. <br> Affichage partiel car base de données Géoreso HS.");

            // Affichage du tableau des infos correctes
            echo $resume.$selecteurLigne.$enteteTableau.$corpsTableau.$piedTableau.$finDePage;
            
        }// Si j'ai uniquement des résultats dans le tableau contenant les informations venues de Géoreso alors je n'affiche que ceux là dans mon tableau
        elseif($tailleResReqGER !== 0 && $tailleResReqIPON === 0 && ($combinaisonHorsRip !== 3 && $combinaisonRip !== 3)){

            for($i = 0; $i < $tailleResReqGER; $i++){
                $index++;
                $corpsTableau .= createLineTab($index, "", "", "", $resultReqGER[$i]['ref_pt'], $resultReqGER[$i]['id_metier_site'], $resultReqGER[$i]['type_site'], "", "");            
            }

            $resume = createResume($index, 0, 0, $index);

            
            saveSearchResultInHistory(" $index. <br> Affichage partiel car base de données IPON HS.");

            // Affichage du tableau des infos correctes
            echo $resume.$selecteurLigne.$enteteTableau.$corpsTableau.$piedTableau.$finDePage;

            
        }
        else{

            // Si les tableaux ne font pas la même taille cela signifie que des ids géoreso n'ont pas trouvés de correspondance dans IPON
            // On doit donc récupérer ces ids et les afficher en haut de notre tableau
            if ($tailleResReqGER !== $tailleResReqIPON){
                
                $tabCompareRedComment = [];
                $tabCompareOrangeComment = [];
                $tabCompareGreenComment = [];

                $countOrange = 0;
                $countRed = 0;
                $countGreen = 0;

                // Fonction de filtrage pour récupérer les objets géoreso dont l'objectid_ipon n'a pas de correspondance dans la base de données IPON
                $filtrage = function($objet) use ($resultReqIPON) {
                    foreach ($resultReqIPON as $objet2) {
                        if (str_replace("R", "", $objet["objectid_ipon"]) === $objet2["OBJECT_ID"]) { // Le code n'applique le remplacement du R par "" que si l'id est de type RIP. En effet, l'OBJECT_ID dans IPON ne commence jamais par un R.
                            // On a trouvé une correspondance, donc on ne veut pas inclure cet objet dans le tableau des non correspondance                           
                            return false;
                        }
                    }
                    // Si on arrive ici, c'est qu'on n'a pas trouvé de correspondance, donc on veut inclure cet objet dans le tableau des id introuvés
                    return true;
                }; 
                
                // On applique le filtre sur $resultReqGER pour obtenir un nouveau tableau ne contenant que les objets qui n'ont pas de correspondance dans $resultReqIPON
                $nouveauTableauAvecIdIntrouvés = array_filter($resultReqGER, $filtrage);

                
                // On récupère dans ce tableau les objets ayant une correspondance dans $resultReqGER et $resultReqIPON
                $tab = array_diff_key($resultReqGER, $nouveauTableauAvecIdIntrouvés);

                
                
                // On a maintenant 3 tableaux :
                /*
                    $tab : contient tous les objets géoreso ayant une correspondance dans IPON
                    $resultReqIPON : contient tous les objets IPON ayant une correspondance dans Géoreso
                    $nouveauTableauAvecIdIntrouvés : contient tous les objets géoreso qui n'ont pas de correspondance dans IPON        
                */

                // On ajoute dans un tableau les infos des objets sans correspondance
                foreach($nouveauTableauAvecIdIntrouvés as $objet){

                    //$index++;
                    $id_ipon = str_replace("R", "", $objet["objectid_ipon"]);
                    //$corpsTableau .= createLineTab($index, $id_ipon, $objet['NUM_PT'], $objet['Site'], $objet['ref_pt'], $objet['id_metier_site'], $objet['type_site'], "Aucune donnée n'a été trouvée dans IPON pour l'id : {$id_ipon}", "orange");                    
                    
                    $tabCompareOrangeComment[] = createLineTab2($id_ipon, $objet['NUM_PT'], $objet['Site'], $objet['ref_pt'], $objet['id_metier_site'], $objet['type_site'], "Aucune donnée n'a été trouvée dans IPON pour l'id : {$id_ipon}", "orange");
                    
                    $countOrange++;
                
                }

                // Réindexage des clés objets pour qu'elles se suivent numériquement (clé du premier objet du tableau => [0], clé du deuxième objet => [1], etc ...)
                $tab = array_values($tab);

                

                if(count($tab) > 1){
                    foreach ($tab as $key => $row) {
                        $object_id_GER[$key] = $row['objectid_ipon'];
                    }

                    array_multisort($object_id_GER, SORT_ASC, $tab);
                }
                
                if($tailleResReqIPON > 1){
                    foreach ($resultReqIPON as $key => $row) {
                        $object_id_ipon[$key] = $row['OBJECT_ID'];
                    }

                    array_multisort($object_id_ipon, SORT_ASC, $resultReqIPON);
                }
                
                

                for($i = 0; $i < count($tab); $i++){
                    $comparateur = conformiteSiteSupport($tab[$i], $resultReqIPON[$i], $tab[$i]['type_site']);
                    

                    if($comparateur[0] == 'red'){
                        $tabCompareRedComment[] = createLineTab2($resultReqIPON[$i]['OBJECT_ID'], $resultReqIPON[$i]['NUM_PT'], $resultReqIPON[$i]['SITE'], $tab[$i]['ref_pt'], $tab[$i]['id_metier_site'], $tab[$i]['type_site'], $comparateur[1], $comparateur[0]);
                    
                        $countRed++;
                    }
                    // if($comparateur[0] == 'orange'){
                    //     $tabCompareOrangeComment[] =  createLineTab2($resultReqIPON[$i]['OBJECT_ID'], $resultReqIPON[$i]['NUM_PT'], $resultReqIPON[$i]['SITE'], $tab[$i]['ref_pt'], $tab[$i]['id_metier_site'], $tab[$i]['type_site'], $comparateur[1], $comparateur[0]);

                    //     $countOrange++;
                    // }
                    if($comparateur[0] == 'green'){
                        $tabCompareGreenComment[] =  createLineTab2($resultReqIPON[$i]['OBJECT_ID'], $resultReqIPON[$i]['NUM_PT'], $resultReqIPON[$i]['SITE'], $tab[$i]['ref_pt'], $tab[$i]['id_metier_site'], $tab[$i]['type_site'], $comparateur[1], $comparateur[0]);

                        $countGreen++;
                    }
                }

                $resume = createResume(($index+$countRed+$countOrange+$countGreen), $countGreen, $countOrange, ($index + $countRed));

                saveSearchResultInHistory(" ".$index+$countGreen+$countOrange+$countRed." <br> Résultats cohérents : $countGreen <br> Résultats sans correspondance : $countOrange <br> Résultats incohérents : ".$countRed + $index."");

                foreach ($tabCompareRedComment as $key => $value) {
                    $index++;
                    $corpsTableau .= "<tr class='o-result__row'><td class='o-result__column'>{$index}</td>";
                    $corpsTableau .= $value;
                }
                foreach ($tabCompareOrangeComment as $key => $value) {
                    $index++;
                    $corpsTableau .= "<tr class='o-result__row'><td class='o-result__column'>{$index}</td>";
                    $corpsTableau .= $value;
                }
                foreach ($tabCompareGreenComment as $key => $value) {
                    $index++;
                    $corpsTableau .= "<tr class='o-result__row'><td class='o-result__column'>{$index}</td>";
                    $corpsTableau .= $value;
                }
                                
            }
            else{

                
                
                $tabCompareRedComment = [];
                $tabCompareOrangeComment = [];
                $tabCompareGreenComment = [];

                $countOrange = 0;
                $countRed = 0;
                $countGreen = 0;

                // Tri des résultats par ordre croissant d'ipon id pour être sûr de les avoir dans le bon ordre
                if(count($resultReqGER) > 1){
                    foreach ($resultReqGER as $key => $row) {
                        $object_id_GER[$key] = $row['objectid_ipon'];
                    }

                    array_multisort($object_id_GER, SORT_ASC, $resultReqGER);
                }
                
                if($resultReqIPON > 1){
                    foreach ($resultReqIPON as $key => $row) {
                        $object_id_ipon[$key] = $row['OBJECT_ID'];
                    }

                    array_multisort($object_id_ipon, SORT_ASC, $resultReqIPON);
                }

                // print_r2($resultReqGER);
                // print_r2($resultReqIPON);

                // Ajout des lignes contenant les infos correctes
                for($i = 0; $i < $tailleResReqIPON; $i++){

                    $index++;
                    
                    $comparateur = conformiteSiteSupport($resultReqGER[$i], $resultReqIPON[$i], $resultReqGER[$i]['type_site']);
                    
                    $tableauInfoCorrectes .= "<td class='o-result__column' style=\"color: ".$comparateur[0].";\">".$comparateur[1]."</td></tr>";

                    if($comparateur[0] == 'green'){
                        $countGreen++;
                        $tabCompareGreenComment[] =  createLineTab2($resultReqIPON[$i]['OBJECT_ID'], $resultReqIPON[$i]['NUM_PT'], $resultReqIPON[$i]['SITE'], $resultReqGER[$i]['ref_pt'], $resultReqGER[$i]['id_metier_site'], $resultReqGER[$i]['type_site'], $comparateur[1], $comparateur[0]);
                    }
                    if($comparateur[0] == 'orange'){
                        $countOrange++;
                        $tabCompareOrangeComment[] =  createLineTab2($resultReqIPON[$i]['OBJECT_ID'], $resultReqIPON[$i]['NUM_PT'], $resultReqIPON[$i]['SITE'], $resultReqGER[$i]['ref_pt'], $resultReqGER[$i]['id_metier_site'], $resultReqGER[$i]['type_site'], $comparateur[1], $comparateur[0]);
                    }
                    if($comparateur[0] == 'red'){
                        $tabCompareRedComment[] = createLineTab2($resultReqIPON[$i]['OBJECT_ID'], $resultReqIPON[$i]['NUM_PT'], $resultReqIPON[$i]['SITE'], $resultReqGER[$i]['ref_pt'], $resultReqGER[$i]['id_metier_site'], $resultReqGER[$i]['type_site'], $comparateur[1], $comparateur[0]);
                        $countRed++;
                    }

                    //$corpsTableau .=  createLineTab($index, $resultReqIPON[$i]['OBJECT_ID'], $resultReqIPON[$i]['NUM_PT'], $resultReqIPON[$i]['SITE'], $resultReqGER[$i]['ref_pt'], $resultReqGER[$i]['id_metier_site'], $resultReqGER[$i]['type_site'], $comparateur[1], $comparateur[0]);
                     
                }

                $resume = createResume($index, $countGreen, $countOrange, $countRed);

                saveSearchResultInHistory(" $index <br> Résultats cohérents : $countGreen <br> Résultats sans correspondance : $countOrange <br> Résultats incohérents : ".$countRed."");

                $index = 0;

                foreach ($tabCompareRedComment as $key => $value) {
                    $index++;
                    $corpsTableau .= "<tr class='o-result__row'><td class='o-result__column'>{$index}</td>";
                    $corpsTableau .= $value;
                }
                foreach ($tabCompareOrangeComment as $key => $value) {
                    $index++;
                    $corpsTableau .= "<tr class='o-result__row'><td class='o-result__column'>{$index}</td>";
                    $corpsTableau .= $value;
                }
                foreach ($tabCompareGreenComment as $key => $value) {
                    $index++;
                    $corpsTableau .= "<tr class='o-result__row'><td class='o-result__column'>{$index}</td>";
                    $corpsTableau .= $value;
                }
                
                  
            }     
            
            // Affichage du tableau des infos correctes
            echo $titre.$resume.$selecteurLigne.$enteteTableau.$corpsTableau.$piedTableau.$finDePage;        

        }
    }
    
}

//message d'erreur pour donnée incorrect ou manquante
else{
    echo "<div class='o-alert'>Veuillez saisir une ou plusieurs données valides.</div>";

    saveSearchResultInHistory('Format de données invalide pour le mode de recherche sélectionné.');
}





?>