
<?php

@include './analyse/test_reference_support.php';


// Récupérer la référence du support passée dans la barre de recherche
$refSupport = trim($_POST['searchBar']);

// Vérifier que la recherche n'est pas vide
// Si elle l'est :
if(empty($refSupport))
{
    // Alerter l'utilisateur
    echo "<div class='o-alert'>Le champs est vide.</div>";
    // Sauvegarder l'action dans les traces
    saveSearchResultInHistory("vide");
}
// Si elle ne l'est pas :
else
{
    // Récupérer le numéro du point technique (= seconde barre de recherche)
    $numPointTechnique = trim($_POST['supportGerRefPT']);

    // Initialiser une variable qui servira à déterminer si le numéro de PT a été renseigné au début. Par défaut, elle est à false (= num PT non renseigné)
    $numPT = false;

    // Si le num PT a été renseigné, on passe la variable à true
    if($numPointTechnique != "" && $numPointTechnique != null)
    {
        $numPT = true;
    }

    // Récupérer l'option sélectionnée (= menu déroulant proposant les types de support)
    $typeSupport = $_POST['supportGerTypeSearch'];

    // En fonction du type de support sélectionné, la référence du support doit avoir une synthaxe particulière. 
    // On vérifie ici que la synthaxe de la référence support est conforme à la synthaxe attendue
    // Renvoie true pour une synthaxe conforme et false pour une synthaxe non conforme
    switch($typeSupport)
    {
        // Pour une recherche sur un immeuble
        case 'gerTypeSearch-Immeuble' :        
            $refSupportCorrect = isRefImmeubleCorrect($refSupport);        
        break;
        // Pour une recherche sur un appui ft
        case 'gerTypeSearch-AppuiFt' :
            $refSupportCorrect = isRefAppuiFtCorrect($refSupport);
        break;
        // Pour une recherche sur un appui erdf
        case 'gerTypeSearch-AppuiErdf' :
            $refSupportCorrect = isRefAppuiErdfCorrect($refSupport);
        break;
        // Pour une recherche sur une chambre
        case 'gerTypeSearch-Chambre' :
            $refSupportCorrect = isRefChambreCorrect($refSupport);
        break;
        // Pour une recherche sur une armoire
        case 'gerTypeSearch-Armoire' :
            $refSupportCorrect = isRefArmoireCorrect($refSupport);
        break;
        default : echo "<div class='o-alert'>Le support sélectionné n'est pas implémenté.</div>";
        break;
    }


    // Si la synthaxe de la référence support est conforme à la synthaxe attendue :
    if($refSupportCorrect)
    {

        $connexionIpon = DatabaseConnectionPool::createConnection('Ipon');
        $connexionIponRip = DatabaseConnectionPool::createConnection('IponRip');
        $connexionGeoreso = DatabaseConnectionPool::createConnection('Georeso');
        $connexionGeoresoRip = DatabaseConnectionPool::createConnection('GeoresoRip');

        // Renvoyer la réponse sous forme de JSON pour être utilisé dans le javascript
        $tabDatabase = ["connexionIpon"=>$connexionIpon[0],"connexionIponRip"=>$connexionIponRip[0],"connexionGeoreso" =>$connexionGeoreso[0],"connexionGeoresoRip"=>$connexionGeoresoRip[0]];

        $tabHeureTest = ["heureTestIpon" => $connexionIpon[1], "heureTestIponRip" => $connexionIponRip[1], "heureTestGeoreso" => $connexionGeoreso[1], "heureTestGeoresoRip" => $connexionGeoresoRip[1]];

        $tabDelais = ["delaisConnexionIpon"=>$connexionIpon[2],"delaisConnexionIponRip"=>$connexionIponRip[2],"delaisConnexionGeoreso" =>$connexionGeoreso[2],"delaisConnexionGeoresoRip"=>$connexionGeoresoRip[2]];


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



        // Ecrire la requête pour récupérer les informations associées à la référence support dans la base de données Géoreso
        $req = "SELECT REPLACE(type_site, 'ftth_site_', '') as type_site,id_metier_site,ref_pt,objectid_ipon,code_com,id_ftth,operateur,usage_FTTH,usage_ROME
                FROM georeso.ftth_point_technique_attr 
                WHERE id_metier_site = '".$refSupport."'          
            ";
        // Si le numéro de point technique avait été renseigné :
        if($numPT)
        {
            // Compléter la requête avec le numéro de point technique pour la rendre plus précise
            $req .= " AND ref_pt = '".$numPointTechnique."'";
        }





        

        if ($connexionGeoreso[0]) {

            //print_r2($connexionGeoreso);

            //include "./config/database_GER.php";
            $pdo = DatabaseConnectionPool::getPDO('Georeso');

            $connectionDatabase = "IPON";

            $combinaisonHorsRip += 2;

            // Executer la requête
            $prep = $pdo-> prepare($req);
            $prep -> execute();
            // Récupérer les résultats 
            $resReq1 = $prep ->fetchAll(PDO::FETCH_ASSOC);

            DatabaseConnectionPool::closeConnection('Georeso');
        }

        // $req = "SELECT *
        //         FROM georeso.ftth_point_technique_attr 
        //         LIMIT 10         
        //     ";
        // $prep = $pdo-> prepare($req);
        // $prep -> execute();
        // $resReq = $prep ->fetchAll(PDO::FETCH_ASSOC);
        // print_r2($resReq);
       


        if(empty($resReq1)){

            if ($connexionGeoresoRip[0]) {
                
                //include "./config/database_GER_RIP.php";

                $pdo = DatabaseConnectionPool::getPDO('GeoresoRip');

                $connectionDatabase = "RIP";

                $combinaisonRip += 2;

                $prep = $pdo-> prepare($req);
                $prep -> execute();
                // Récupérer les résultats 
                $resReq1 = $prep ->fetchAll(PDO::FETCH_ASSOC);

                DatabaseConnectionPool::closeConnection('GeoresoRip');
            }
        }




        $affichageAnalyseIpon = true;
        

        switch ($connectionDatabase) {
            case 'IPON':
                $pdo = DatabaseConnectionPool::getPDO('Ipon');

                $req = requestSiteExisteDansIpon($refSupport);
                $prep =  $pdo->prepare($req);
                $prep->execute();
                $resSiteIpon = $prep->fetchAll(PDO::FETCH_ASSOC);
            
                $req = requestPtIpon($refSupport);
                $prep =  $pdo->prepare($req);
                $prep->execute();
                $resPtIpon = $prep->fetchAll(PDO::FETCH_ASSOC);
            break;

            case 'RIP':
                $pdo = DatabaseConnectionPool::getPDO('IponRip');

                $req = requestSiteExisteDansIpon($refSupport);
                $prep =  $pdo->prepare($req);
                $prep->execute();
                $resSiteIpon = $prep->fetchAll(PDO::FETCH_ASSOC);
            
                $req = requestPtIpon($refSupport);
                $prep =  $pdo->prepare($req);
                $prep->execute();
                $resPtIpon = $prep->fetchAll(PDO::FETCH_ASSOC);
            break;
            
            default:
                $affichageAnalyseIpon = false;
            break;
        }


        // Analyse des résultats : exemple multiple -> chambre 00342/46127



        $comparatif = "<section class='compareSection'>";
        $comparatif .= "<div class='compareSubSection'>";
        $comparatif .= "<h2 class='titre2'>DANS IPON : </h2>";
        $comparatif .= "<h3 class='titre3'>Informations pour l'id métier : <span class='orangeColor'>$refSupport</span> dans la base de données d'Ipon</h3>";
        $comparatif .= "<div class='partieInfos'>";
        $comparatif .= "<h4 class='titre4'>Partie site :</h4>";
        if(empty($resSiteIpon))
        {
            $comparatif .= "<p class='redColor'>Pas d'informations pour cet id métier dans la base de données d'Ipon.</p>";
        }
        else
        {
            for ($i=0; $i < count($resSiteIpon); $i++) { 
                # code...
            

                $comparatif .= "<ul>";
                foreach ($resSiteIpon[$i] as $key => $value) {
                    $comparatif .= "<li class='grilleInfo'><div class='partieGauche'>$key : </div>  <div class='partieDroite'>$value</div></li>";
                }
                $comparatif .= "</ul>";

            }

        }
        $comparatif .= "</div>";
        
        


        $comparatif .= "<div class='partieInfos'>";
        $comparatif .= "<h4 class='titre4'>Partie PT :</h4>";
        if(empty($resPtIpon))
        {
            $comparatif .= "<p class='redColor'>Aucun PT trouvé pour cet id métier dans la base de données d'Ipon.</p>";
        }
        else
        {
            for ($i=0; $i < count($resPtIpon); $i++) {

                $comparatif .= "<ul>";
                foreach ($resPtIpon[$i] as $key => $value) {
                    $comparatif .= "<li class='grilleInfo'><div class='partieGauche'>$key : </div> <div class='partieDroite'>$value</div></li>";
                }
                $comparatif .= "</ul>";

            }
        }
        $comparatif .= "</div>";
        $comparatif .= "</div>";





        $comparatif .= "<div class='compareSubSection'>";
        $comparatif .= "<h2 class='titre2'>DANS GEORESO : <h2>";
        $comparatif .= "<h3 class='titre3'>Informations pour l'id métier : <span class='orangeColor'>$refSupport</span> dans la base de données de Géoreso</h3>";
        $comparatif .= "<div class='partieInfoGeoreso'>";
        if(empty($resReq1))
        {
            $comparatif .= "<p class='redColor'>Pas d'informations pour cet id métier dans la base de données de Géoreso.</p>";
        }
        else
        {
            for ($i=0; $i < count($resReq1); $i++) { 
            
                $comparatif .= "<ul>";
                foreach ($resReq1[$i] as $key => $value) {
                    $comparatif .= "<li class='grilleInfo'><div class='partieGauche'>$key : </div> <div class='partieDroite'>$value</div></li>";
                }
                $comparatif .= "</ul><br>";

            }
        }
        $comparatif .= "</div>";
        $comparatif .= "</div>";


        $comparatif .= "</section>";


        //echo $comparatif;

        // Récupérer la taille du tableau contenant les résultats de la requêtes sur la basse de données Géoreso
        $tailleResReq1 = count($resReq1);

        // Si la requête n'a pas fourni de résultat : 
        if($tailleResReq1 == 0 || $tailleResReq1 == null)
        {
            // Initialiser un message d'alerte
            $message = "<div class='o-alert'>Aucun résultat trouvé dans Géoreso pour la référence support : ".$refSupport;

            // Si on avait un numéro de point technique :
            if($numPT)
            {
                // Compléter le message
                $message .= " à la référence PT : ".$numPointTechnique.".</div>";
                // Sauvegarder dans les traces
                saveSearchResultInHistory("Aucun résultat trouvé dans Géoreso pour cette référence support associée à ce point technique.");
            }
            else{
                // Compléter le message
                $message .= ".</div>";
                // Sauvegarder dans les traces
                saveSearchResultInHistory("Aucun résultat trouvé dans Géoreso pour cette référence support.");
            }
            // Afficher le message
            echo $message;            
        }
        // Si on a des résultats pour la première requête :
        else
        {   
            // Initialisation des variables qui serviront à afficher le contenu

            $titre = "<h2 class='titre2'>RÉSULTATS VUS DE GEORESO : </h2>";
            
            // Ces 3 là serviront à construire le cadre résumant les résultats obtenus
            $debutResume = "<section class='recapResult'>";
            $resume = "";
            $finResume = "</section>";

            // Si on a plus de 5 résultats pour la première requête : 
            if($tailleResReq1 > 5){

                // Affichage du menu déroulant pour le choix du nombre de ligne à afficher
                // Le palier minimale est de 5 lignes affichées 
                $selecteurLigne = "<div class='wrapper_insider'> 
                        <label for='row_select'> Afficher : </label>
                        <select name='row_select' id='row_select'>
                            <option value='5'>5</option>
                            <option value='10'>10</option>
                            <option value='15' selected='selected'>15</option>
                            <option value='20'>20</option>
                            <option value='30'>30</option>";
                
                // Si on a plus de 30 résultats : 
                if($tailleResReq1 > 30){ 
                    // Afficher l'option d'afficher toutes les lignes
                    $selecteurLigne .= "<option value='".$tailleResReq1."'>".$tailleResReq1."</option>.";
                }

                // Fermer le bloc 
                $selecteurLigne .= "</select><div> lignes</div> </div>";
            }

            // Initialiser le tableau contenant les résultats
            
            // Initialiser l'entête du tableau avec le nom des colonnes
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

            // Initialiser le pied du tableau
            $piedTableau = "</table></div>";

            // Initialiser l'affichage de fin de page
            // Si on a moins de 5 résultats :
            if($tailleResReq1 < 5){
                // Initialiser l'affichage de fin de page de façon minimaliste
                $finDePage = "
                <div class='pagination-info'>
                    <p class='ligne'></p>
                </div>";
            }
            // Si on a 5 résultats ou plus :
            else {
                // Initialiser la fin de page avec l'affichage de la navigation
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

            // Initialiser le corps du tableau
            $corpsTableau = "";

            
            // On va préparer la chaine d'id ipon qu'on passera à notre deuxième requête
            // Pour chaque résultat obtenu pour la première requête
            for($i = 0; $i < $tailleResReq1; $i++) {
                
                // Supprimer toutes les lettres de l'id ipon récupéré et l'ajouter à la chaine récupérant ces ids
                $chaineIponId .= trim(preg_replace("/[A-Za-z]/","",$resReq1[$i]['objectid_ipon']));

                // Si l'id ipon traiter n'est pas le dernier des résultats récupérés :
                if($i < $tailleResReq1-1){
                    // Ajouter une virgule pour le séparer de l'id suivant
                    $chaineIponId .= "','";
                }               

            }

            

            
            // Ecrire la requête pour récupérer les informations associées à la chaine d'id ipon créée précédemment dans la base de données Géoreso
            $requestPT = "WITH cte AS (
                SELECT NAME,OBJECT_ID,PARENT_ID,OBJECT_TYPE_ID
                FROM NC_OBJECTS 
                WHERE OBJECT_ID IN ('".$chaineIponId."')
            )SELECT n.NAME AS SITE, c.NAME Num_PT, c.OBJECT_ID, c.PARENT_ID,c.OBJECT_TYPE_ID FROM NC_OBJECTS n 
            JOIN  cte c ON c.PARENT_ID = n.OBJECT_ID
            WHERE n.OBJECT_ID IN (SELECT PARENT_ID FROM cte)
            ORDER BY NUM_PT";


            if ($connexionIpon[0] && $connectionDatabase == "IPON") {
                //include "./config/database_IPON.php";
    
                $bdd = DatabaseConnectionPool::getPDO('Ipon');

                $connectionDatabase = "IPON";
    
                $combinaisonHorsRip +=1;

                // Executer la requête
                $prepareRequestPT = $bdd->prepare($requestPT);
                $prepareRequestPT->execute();

                // Récupérer les résultats dans un tableau
                $resReq2 = $prepareRequestPT->fetchAll(PDO::FETCH_ASSOC);

                DatabaseConnectionPool::closeConnection('Ipon');
            }
    
            if ($connexionIponRip[0] && $connectionDatabase == "RIP") {
                //include "./config/database_RIP.php";

                $bdd = DatabaseConnectionPool::getPDO('IponRip');
    
                $connectionDatabase = "RIP";
    
                $combinaisonRip +=1;

                // Executer la requête
                $prepareRequestPT = $bdd->prepare($requestPT);
                $prepareRequestPT->execute();

                // Récupérer les résultats dans un tableau
                $resReq2 = $prepareRequestPT->fetchAll(PDO::FETCH_ASSOC);

                DatabaseConnectionPool::closeConnection('IponRip');
            }

            

            // Récupérer la taille du tableau contenant les résultats de la requêtes sur la base de données IPON
            $tailleResReq2 =  count($resReq2);

            // Initialisation des tableaux qui serviront à générer l'affichage du tableau final des résultats 
            $tableauIdSansCorrespondance = [];
            $tableauIdAvecCorrespondance = [];
            $tableauIdAvecCorrespondanceMessageVert = [];
            $tableauIdAvecCorrespondanceMessageOrange = [];
            $tableauIdAvecCorrespondanceMessageRouge = [];

            // Initialisation de l'index pour la numérotation des lignes du tableau final
            $index = 0;

            
            // On va comparer les tailles des tableaux contenant les résultats des deux requêtes
            // Par souci de clareté, cette comparaison apparait dans un switch avec un test à true, ce qui signifie qu'on passe obligatoirement dedans
            switch (true) 
            {
                // Dans le cas où on aurait pas obtenu de résultat à la seconde requête :
                case ($tailleResReq2 == 0 || $tailleResReq2 == null) :

                    // Celà signifie qu'on a trouvé aucun résultats correspondant aux id ipon extraits de la requête sur la base de données Géoreso dans la base de sonnées IPON
                    // Mais, puisque nous somme arrivé à cette étape, celà signifie aussi que des résultats ont été trouvé pour la requête sur la bdd Géoreso
                    // Il est donc intéressant de les afficher

                    // Pour chaque résultats de la première requête
                    foreach ($resReq1 as $objet) {

                        // Incrémenter l'index
                        $index++;
                        // Supprimer les 'R' de chaque id ipon (s'il y en a)
                        $id_ipon = str_replace("R", "", $objet["objectid_ipon"]);

                        // Créer une ligne complète qu'on ajoute au corps du tableau
                        // Cette ligne contiendra les résultats de la première requête
                        // Ces résultats apparaitront dans la partie Géoreso du tableau (= à gauche)
                        // On ajoute égalemment un commentaire de couleur rouge 
                        // Dans la partie gauche du tableau, on affiche le numéro de la ligne (= index) et l'id ipon extrait de la requête et mis au format hors-rip (= sans le 'R' car dans la bd IPON, les id ipon apparaissent sans le 'R' mais s'il s'agit d'un rip dans géoreso)
                        $corpsTableau .=  createLineTab($index, $id_ipon, "", "", $objet['ref_pt'], $objet['id_metier_site'], $objet['type_site'], "Aucune donnée n'a été trouvée dans IPON pour l'id : {$id_ipon}", "orange");
                    }

                    // Création du résumé auquel on passe le nombre de résultats obtenus et le nombre de résultats correctes, incohérents et incorrectes 
                    $resume = createResume($index, 0, $index, 0);

                    // Sauvegarde de la recherche dans l'historique avec un message
                    saveSearchResultInHistory("Aucune donnée n'a été trouvée dans IPON pour l'id : {$id_ipon}");
                    
                break;

                // Dans le cas où on aurait obtenu un même nombre de résultats pour les 2 requêtes :
                case ($tailleResReq2 == $tailleResReq1) :

                    
                    // Initialiser 2 tableaux 
                    $object_id_GER = [];
                    $object_id_ipon = [];

                    // Initialiser les compteurs pour écrire le résumé 
                    $countOrange = 0;
                    $countRed = 0;
                    $countGreen = 0;

                    // Si on a plus d'un résultat pour la première requête :
                    if($tailleResReq1 > 1){
                        // Pour chaque résultat
                        foreach ($resReq1 as $key => $row) {
                            // Récupérer l'id ipon
                            $object_id_GER[$key] = $row['objectid_ipon'];
                        }
                        // Trier le tableau des résultats de la requête Géoreso dans l'ordre croissant en fonction de la valeur des ids ipon
                        array_multisort($object_id_GER, SORT_ASC, $resReq1);
                    }
                    
                    // Même chose pour les résultats de la seconde requête 
                    // Si on a plus d'un résultat :
                    if($tailleResReq2 > 1){
                        // Pour chaque résultat
                        foreach ($resReq2 as $key => $row) {
                            // Récupérer l'id ipon (correspondant à l'object id pour les résultats récupérer dans la bdd IPON)
                            $object_id_ipon[$key] = $row['OBJECT_ID'];
                        }
                        // Trier le tableau des résultats de la requête IPON dans l'ordre croissant en fonction de la valeur des ids ipon
                        array_multisort($object_id_ipon, SORT_ASC, $resReq2);
                    }

                    // Comme nos tableaux contenant les résultats des deux requêtes font la même taille, on est sur de pouvoir comparer leurs valeurs

                    // Effectuer une boucle pour comparer les deux tableaux
                    for($i = 0; $i < $tailleResReq1; $i++){

                        // Comparer les informations 
                        // La fonction renvoie un tableau avec la conclusion de l'analyse et la couleurs du message de conclusion
                        $comparateur = conformiteSiteSupport($resReq1[$i], $resReq2[$i], $resReq1[$i]['type_site']);

                        // Si la couleur est rouge 
                        if($comparateur[0] == 'red'){
                            // Créer le contenu d'une ligne du tableau finale qu'on stocke dans le tableau des messages rouges
                            $tableauIdAvecCorrespondanceMessageRouge[] = createLineTab2($resReq2[$i]['OBJECT_ID'], $resReq2[$i]['NUM_PT'], $resReq2[$i]['SITE'], $resReq1[$i]['ref_pt'], $resReq1[$i]['id_metier_site'], $resReq1[$i]['type_site'],$comparateur[1], $comparateur[0]);
                            // On incrémente le compteur de messages rouges
                            $countRed++;
                        }
                        // Faire de même pour les messages oranges
                        if($comparateur[0] == 'orange'){

                            $tableauIdAvecCorrespondanceMessageOrange[] =  createLineTab2($resReq2[$i]['OBJECT_ID'], $resReq2[$i]['NUM_PT'], $resReq2[$i]['SITE'], $resReq1[$i]['ref_pt'], $resReq1[$i]['id_metier_site'], $resReq1[$i]['type_site'],$comparateur[1], $comparateur[0]);

                            $countOrange++;
                        }
                        // Faire de même pour les messages verts
                        if($comparateur[0] == 'green'){
                            $tableauIdAvecCorrespondanceMessageVert[] =  createLineTab2($resReq2[$i]['OBJECT_ID'], $resReq2[$i]['NUM_PT'], $resReq2[$i]['SITE'], $resReq1[$i]['ref_pt'], $resReq1[$i]['id_metier_site'], $resReq1[$i]['type_site'],$comparateur[1], $comparateur[0]);

                            $countGreen++;
                        }
                    }

                    // On peut alors compléter nos lignes partiellement créées en html avec le bon entête (= bon index) et créer ainsi le tableau final en mettant les messages rouges en premier, puis les oranges, puis les verts
                    // Comme ça, on a les ids gégérant des erreurs en haut du tableau final
                    foreach ($tableauIdAvecCorrespondanceMessageRouge as $key => $value) {
                        $index++;
                        $corpsTableau .= "<tr class='o-result__row'><td class='o-result__column'>{$index}</td>";
                        $corpsTableau .= $value;
                    }
                    foreach ($tableauIdAvecCorrespondanceMessageOrange as $key => $value) {
                        $index++;
                        $corpsTableau .= "<tr class='o-result__row'><td class='o-result__column'>{$index}</td>";
                        $corpsTableau .= $value;
                    }
                    foreach ($tableauIdAvecCorrespondanceMessageVert as $key => $value) {
                        $index++;
                        $corpsTableau .= "<tr class='o-result__row'><td class='o-result__column'>{$index}</td>";
                        $corpsTableau .= $value;
                    }

                    // Créer le résumé 
                    $resume = createResume($index, $countGreen, $countOrange, $countRed);

                    // Sauvegarder le résultats dans l'historique 
                    saveSearchResultInHistory(" ".$countGreen+$countOrange+$countRed." <br> Résultats cohérents : $countGreen <br> Résultats sans correspondance : $countOrange <br> Résultats incohérents : $countRed");
                    
                break;

                // Dans le cas où on a plus de résultats pour la deuxième requête que pour la première (= ce qui ne devrait absolument pas être possible ) :
                case ($tailleResReq2 > $tailleResReq1) :
                    // Alerter l'utilisateur
                    echo "<div class='o-alert'>Erreur : Un ou plusieurs de vos id Géoreso ont plusieurs correspondances dans la base IPON.</div>";

                    // Faire un résumé vide
                    $resume = createResume("", "", "", "");

                    // Sauvegarder le résultat dans l'historique
                    saveSearchResultInHistory("Un ou plusieurs de vos id Géoreso ont plusieurs correspondances dans la base IPON.");
                break;


                // Dans le cas où tous les ids ipon récupérés dans la bdd Géoreso n'aurez pas tous une correspondance avec les résultats récupérés dans la bdd IPON :
                case ($tailleResReq2 < $tailleResReq1) :    
                    
                    

                    // Initialiser 2 tableaux 
                    $object_id_GER = [];
                    $object_id_ipon = [];

                    // Initialiser les compteurs pour écrire le résumé 
                    $countOrange = 0;
                    $countRed = 0;
                    $countGreen = 0;

                    // Fonction de filtrage pour récupérer les objets géoreso dont l'objectid_ipon n'a pas de correspondance dans la base de données IPON
                    $filtrage = function($objet) use ($resReq2) {
                        foreach ($resReq2 as $objet2) {
                            if (str_replace("R", "", $objet["objectid_ipon"]) === $objet2["OBJECT_ID"]) { // Le code n'applique le remplacement du R par "" que si l'id est de type RIP. En effet, l'OBJECT_ID dans IPON ne commence jamais par un R.
                                // On a trouvé une correspondance, donc on ne veut pas inclure cet objet dans le tableau des non correspondance                           
                                return false;
                            }
                        }
                        // Si on arrive ici, c'est qu'on n'a pas trouvé de correspondance, donc on veut inclure cet objet dans le tableau des id introuvés
                        return true;
                    }; 
                    
                    // On applique le filtre sur $resReq1 pour obtenir un nouveau tableau ne contenant que les objets qui n'ont pas de correspondance dans $resReq2
                    $tableauIdSansCorrespondance = array_filter($resReq1, $filtrage);
                   
                    // On récupère dans ce tableau les objets ayant une correspondance dans $resReq1 et $resReq2
                    $tableauIdAvecCorrespondance = array_diff_key($resReq1, $tableauIdSansCorrespondance);
                    
                    // On ajoute au corsp du tableau final les infos des objets sans correspondance
                    foreach($tableauIdSansCorrespondance as $objet){
                        $index++;
                       
                        $id_ipon = str_replace("R", "", $objet["objectid_ipon"]);
                        
                        //$corpsTableau .= createLineTab($index, $id_ipon, $objet['NUM_PT'], $objet['Site'], $objet['ref_pt'], $objet['id_metier_site'], $objet['type_site'], "Aucune donnée n'a été trouvée dans IPON pour l'id : {$id_ipon}", "red");
                        $tableauIdAvecCorrespondanceMessageOrange[] = createLineTab2($id_ipon, $objet['NUM_PT'], $objet['SITE'], $tableauIdAvecCorrespondance['ref_pt'], $objet['id_metier_site'], $objet['type_site'], "Aucune donnée n'a été trouvée dans IPON pour l'id : {$id_ipon}", "orange");
                        $countOrange++;                      
                    
                    
                    }

                    // Réindexage des clés objets pour qu'elles se suivent numériquement (clé du premier objet du tableau => [0], clé du deuxième objet => [1], etc ...)
                    $tableauIdAvecCorrespondance = array_values($tableauIdAvecCorrespondance);


                    // Trier le tableau des objets avec correspondance par id ipon croissant 
                    if(count($tableauIdAvecCorrespondance) > 1){
                        foreach ($tableauIdAvecCorrespondance as $key => $row) {
                            $object_id_GER[$key] = $row['objectid_ipon'];
                        }
                        array_multisort($object_id_GER, SORT_ASC, $tableauIdAvecCorrespondance);
                        
                    }
                    
                    // Trier le tableau des données de la requêtes sur la bdd IPON par id ipon croissant 
                    if($tailleResReq2 > 1){
                        foreach ($resReq2 as $key => $row) {
                            $object_id_ipon[$key] = $row['OBJECT_ID'];
                        }
                        array_multisort($object_id_ipon, SORT_ASC, $resReq2);
                    }
                    
                    // Maintenant qu'on a éliminé les objets n'ayant pas de correspondance, on peut s'occuper de ceux ayant une correspondance
                    for($i = 0; $i < count($tableauIdAvecCorrespondance); $i++){

                        // Comparer les objets 
                        $comparateur = conformiteSiteSupport($tableauIdAvecCorrespondance[$i], $resReq2[$i], $tableauIdAvecCorrespondance[$i]['type_site']);

                        // Créer la ligne du tableau correspondant à chaque couleur de message

                        if($comparateur[0] == 'red'){
                            $tableauIdAvecCorrespondanceMessageRouge[] = createLineTab2($resReq2[$i]['OBJECT_ID'], $resReq2[$i]['NUM_PT'], $resReq2[$i]['SITE'], $tableauIdAvecCorrespondance[$i]['ref_pt'], $tableauIdAvecCorrespondance[$i]['id_metier_site'], $tableauIdAvecCorrespondance[$i]['type_site'], $comparateur[1], $comparateur[0]);
                            $countRed++;
                        }
                        if($comparateur[0] == 'orange'){
                            $tableauIdAvecCorrespondanceMessageOrange[] = createLineTab2($resReq2[$i]['OBJECT_ID'], $resReq2[$i]['NUM_PT'], $resReq2[$i]['SITE'], $tableauIdAvecCorrespondance[$i]['ref_pt'], $tableauIdAvecCorrespondance[$i]['id_metier_site'], $tableauIdAvecCorrespondance[$i]['type_site'], $comparateur[1], $comparateur[0]);
                            $countOrange++;
                        }
                        if($comparateur[0] == 'green'){
                            $tableauIdAvecCorrespondanceMessageVert[] = createLineTab2($resReq2[$i]['OBJECT_ID'], $resReq2[$i]['NUM_PT'], $resReq2[$i]['SITE'], $tableauIdAvecCorrespondance[$i]['ref_pt'], $tableauIdAvecCorrespondance[$i]['id_metier_site'], $tableauIdAvecCorrespondance[$i]['type_site'], $comparateur[1], $comparateur[0]);
                            $countGreen++;
                        }
                    }

                    $index = 0;

                    // Créer le résumé
                    $resume = createResume(($countGreen+$countOrange+$countRed), $countGreen, $countOrange, $countRed);
                    
                    // Sauvegarder le résultat dans l'historique
                    $resultSummary = " ".$countGreen+$countOrange+$countRed+$index." <br> Résultats cohérents : $countGreen <br> Résultats sans correspondance : $countOrange <br> Résultats incohérents : ".$countRed + $index."";
                    saveSearchResultInHistory($resultSummary);
                    
                    // Compléter le code html de chaque ligne
                    foreach ($tableauIdAvecCorrespondanceMessageRouge as $key => $value) {
                        $index++;
                        $corpsTableau .= "<tr class='o-result__row'><td class='o-result__column'>{$index}</td>";
                        $corpsTableau .= $value;
                    }
                    foreach ($tableauIdAvecCorrespondanceMessageOrange as $key => $value) {
                        $index++;
                        $corpsTableau .= "<tr class='o-result__row'><td class='o-result__column'>{$index}</td>";
                        $corpsTableau .= $value;
                    }
                    foreach ($tableauIdAvecCorrespondanceMessageVert as $key => $value) {
                        $index++;
                        $corpsTableau .= "<tr class='o-result__row'><td class='o-result__column'>{$index}</td>";
                        $corpsTableau .= $value;
                    }
                
                break;
                
                default:
                    echo "<div class='o-alert'>Erreur : cas de traitement non gérer.</div>";
                break;
            }

            // Une fois que toutes les informations sont réunies
            // Afficher le résumé, le sélecteur du nombre de ligne, le tableau des résultats et la navigation 

            if($connectionDatabase == 'RIP'){
                $baseRecherche = 'bdd RIP';
            }
            else{
                $baseRecherche = 'bdd HORS RIP';
            }

            echo "<h3 class='o-result__m-dataSearch'><span>Donnée recherchée sur ".$baseRecherche." : </span></br>". $refSupport."</h3>";
            echo $comparatif.$titre.$resume.$selecteurLigne.$enteteTableau.$corpsTableau.$piedTableau.$finDePage;
            
        }
    }
    // Si la synthaxe de la référence support n'est pas conforme à celle attendue :
    else{
        // Enregistrer dans les traces le résultat
        saveSearchResultInHistory("Le format de la référence support est incorrect.");
    }
}

?>