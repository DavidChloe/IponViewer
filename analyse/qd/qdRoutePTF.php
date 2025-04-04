<?php




// Initialisation du timer
$time_start = microtime(true);

// Récupération de la base sélectionnée
$connectionDatabase = $_POST["database"];

$ndOrVia = $_POST['nd_or_via'];

// Création du PDO pour les requêtes
if($connectionDatabase == 'IPON'){
    DatabaseConnectionPool::createConnection('Ipon');
    $pdo = DatabaseConnectionPool::getPDO('Ipon');

    $name_file = "export_qd_route_ptf_VIA_ipon.csv";
}
else{
    DatabaseConnectionPool::createConnection('IponRip');
    $pdo = DatabaseConnectionPool::getPDO('IponRip');

    $name_file = "export_qd_route_ptf_VIA_iponrip.csv";
}


$string = "<div class='p-search__result'>";

// ajout menu déroulant pour le choix du nombre de ligne à afficher
$selecteurLigne = "<div class='wrapper_insider'> 
        <label for='row_select'> Afficher : </label>
        <select name='row_select' id='row_select'>
            <option value='5'>5</option>
            <option value='10'>10</option>
            <option value='15' selected='selected'>15</option>
            <option value='20'>20</option>
            <option value='30'>30</option>";

$selecteurLigne .= "</select><div> lignes</div> </div>";

$string .= $selecteurLigne;

// Création de l'entête du tableau
$string.= "<table class='o-result p-search__result pagination' data-max-page='15'>";
$string.= " <tr>
                <th colspan='4' class='o-result__column'>
                    <p id='telechargement' style='cursor: pointer;'>
                        <img src='../public/images/download.png' alt='Download' width='16px'><br/>
                        Extraction au Format .csv                    
                    </p>
                </th>
            </tr>
            <tr class='o-result__row__title'>
                <th class='o-result__column'><b>VIA</b></th>
                <th class='o-result__column'><b>OBJECT_ID</b></th>
                <th class='o-result__column'><b>REFERENCE FIBER ROUTE</b></th>
                <th class='o-result__column'><b>NON CONFORMITÉ</b></th>
            </tr>";

// Variable pour l'export QD
$tabqd = [];
$tabqd[] = ['VIA', 'OBJECT_ID', 'REFERENCE FIBER ROUTE', 'NON CONFORMITÉ'];

// Tableau de récupération de tous les résultats pour traitement 
$tabTraitement = [];



ini_set('max_execution_time', 300); // Augmente la limite de fetching à 300 secondes (5 minutes)

// Récupération du nombre de résultats sur laquel la réquête va s'éxécuter
$num_fetch_row = $_POST['num_fetch_row'];

// Initialisation du nombre final de résultats non conformes 
$nbResult = 0;

// Requête pour récupérer les X premiers VIA de la table nc_params_ix parmis les dossiers clients
// 8050755945013937881 -> dossiers clients
$reqVIAorND = "SELECT * FROM nc_params_ix WHERE ix_key ";
// Adapter la requête en fonction de si on veut des VIA ou des ND
if($ndOrVia == 'ND'){
    $reqVIAorND .= "NOT ";
}
// Compléter la requête
$reqVIAorND .=  "LIKE 'VIA%' AND attr_id = 8050755945013937881 FETCH FIRST ".$num_fetch_row." ROWS ONLY";
$prepareReqVIAorND = $pdo->prepare($reqVIAorND);
$prepareReqVIAorND->execute();
$resultReqVIAorND = $prepareReqVIAorND->fetchAll(PDO::FETCH_ASSOC);

// Pour chacun des VIA récupérer, éxécuter la boucle
foreach ($resultReqVIAorND as $key => $value) {
    
    // Récupérer le VIA
    $via = $value['IX_KEY'];
    // Récupérer l'OBJECT_ID
    $objId = $value['OBJECT_ID'];

    // Requête pour récupérer les informations associées à l'object_id dans NC_REFERENCES
    $requeteRoutePTF = "select a.NAME as REF_NAME, r. ATTR_ID, r.REFERENCE, o.name as OBJECT_NAME, o2.name OBJECT2_NAME, r.OBJECT_ID
            from nc_references r, nc_objects o, nc_objects o2, nc_attributes a
            where a.attr_id = r.attr_id and r.reference = o.object_id and r.object_id = o2.object_id and r.object_id = '$objId'";

    $prepareReq = $pdo->prepare($requeteRoutePTF);
    $prepareReq->execute();
    $resultReq = $prepareReq->fetchAll(PDO::FETCH_ASSOC);

    // Initialiser le tableau récupérant les références des fiber route
    $reference = array();

    // Pour chaque résultat de la requête
    foreach ($resultReq as $key => $value) {
        
        // Pour chaque valeur de chaque tableaux issues de la requête
        foreach ($value as $k => $v) {
            // Si la valeur examinée avec pour clé REF_NAME et comme valeur Fiber Route
            if($k == "REF_NAME" && $v == "Fiber Route"){
                // Récupérer la valeur du champs REFERENCE
                $reference[] = $value["REFERENCE"];
            }
        }
    }

    // Initialiser le tableau récupérant les informations concernant l'état de la route PTF pour chaque statut, pour chaque VIA
    $result = array();

    // Pour chaque référence récupérée précédemment
    for ($i=0; $i < count($reference); $i++) { 
    
        // Exécuter la requête permettant de récupérer l'état de la route PTF
        $req = "SELECT a.NAME, anls.NAME, p.LIST_VALUE_ID, l.value, v2.value
                FROM nc_attributes a, nc_nls_attributes anls, nc_params p, nc_list_values l, nc_nls_list_values v2
                WHERE a.attr_id = p.attr_id
                AND p.ATTR_ID IN (6041465672013906929,8061162509013299733,9140727527713904375)
                AND anls.attr_id(+) = p.attr_id
                AND l.list_value_id(+) = p.list_value_id
                AND v2.list_value_id(+) = p.list_value_id
                AND p.object_id = '".$reference[$i]."'";

        $prepareReq = $pdo->prepare($req);
        $prepareReq->execute();
        $resultReq = $prepareReq->fetchAll(PDO::FETCH_ASSOC);

        // Initialiser la variable d'incrémentation à 2 car c'est à partir de la case au rang 2 du tableau (donc la 3eme case) que l'on va stocker les statuts non conformes
        $i = 2;
        

        
        
        // Pour chaque état (logique, RPM et completude)
        foreach ($resultReq as $key => $value) {
            
            // Initialisation de la variable de passage, 0 pour un statut conforme, 1 pour un statut non conforme
            // On ne récupère que les noms des statuts non conforme donc lorsque passage est à 1
            $passage = 0;


            // En fonction de la valeur du champs NAME (= le nom du statut), effectué le traitement qui convient pour l'affichage
            switch($value['NAME']){

                case 'Statut RPM': 
                    // Pour être conforme, doit avoir comme valeur soit Confirme soit PLP sinon est non conforme
                    if(!in_array($value['VALUE'], array('Confirme', 'PLP'))){
                       
                        // Récupère le nom du statut
                        $statut = $value["NAME"];
                        $passage++;
                    }
        
                ;
                break;

                
                case 'Statut de completude': 
                    // Pour être conforme, doit avoir comme valeur soit Complet soit Partiel sinon est non conforme
                    if(in_array($value['VALUE'], array('Complet', 'Partiel'))){

                        
                        if(substr(trim($via), 0, 3) == 'VIA' && $value['VALUE'] == 'Complet'){
                            
                            // Récupère le nom du statut
                            $statut = $value["NAME"];
                            $passage++;
                        }
                    }
                    else{
                        
                        // Récupère le nom du statut
                        $statut = $value["NAME"];
                        $passage++;
                    }
                ;
                break;

                case 'Etat logique': 
                    // Pour être conforme, doit avoir comme valeur soit Actif soit Equipe sinon est non conforme
                    if(!in_array($value['VALUE'], array('Actif', 'Equipe'))){
                       
                        // Récupère le nom du statut
                        $statut = $value["NAME"];
                        $passage++;
                    }
                ;
                break;


                default :
                break;

            }

            

            // Si j'ai un statut non conforme
            if($passage != 0){

                // Récupérer les informations inhérentes

                // Le rang 0 récupère la référence
                $result["$via"][0] = $reference[0];
                // le rang 2 récupère l'object_id
                $result["$via"][1] = $objId;

                // le rang i récupère le nom du statut
                $result["$via"][$i] = $statut; 
                // Incrémenter i pour pouvoir ajouter un autre nom de statut si on rencontre un nouveau statut non conforme 
                $i++;
            }
          
        }
    }

    // stocker chaque tableau contenant des non conformes 
    $tabTraitement[] = $result;
}

// Pour chaque tableau ainsi récupérer
foreach ($tabTraitement as $key => $value) {
    
    // Entrer dans le tableau dont la clé est le VIA
    foreach ($value as $k => $v) {
        
        // Initialisation de la viriable par défaut
        $statuts_non_conforme = "";

        // Si ce tableau a une taille supérieure à 2, soit si des statuts non conformes sont enregistrés
        if(count($v) > 2){

            // Incrémenter le décompte du nombre de résultats
            $nbResult++;

            // Créer une ligne dans le tableau qui sera affiché
            /*
                $k contient le VIA
                $v[1] contient l'object_id
                $v[0] contient la référence de la fiber route
                $statuts_non_conforme contient les noms des statuts non conformes
            */
            $string.= "
            <tr class='o-result__row'>
                <td class='o-result__column'>{$k}</td>
                <td class='o-result__column'>{$v[1]}</td>
                
                <td class='o-result__column'>{$v[0]}</td>
                <td class='o-result__column'>";

                // Pour chaque statut non conformes, récupérer leurs nom et les ajouter à une chaine de caractères qui sera utilisée pour compléter le tableau
                for($j = 2; $j < count($v); $j++) {
                    
                    $statuts_non_conforme .= $v[$j];
                    
                    if($j < count($v)-1){
                         // Ajouter une virgule entre chaque nom si on est pas au dernier de la liste
                        $statuts_non_conforme .= ", ";
                    }
                }
            // Ajout de cette chaine dans la case du tableau à afficher
            $string .= $statuts_non_conforme;

            // Récupérer les informations dans un autre tableau pour l'export CSV
            $tabqd[] = [$k, $v[1], $v[0], $statuts_non_conforme];
            // Fermer la ligne
            $string .="</td></tr>";    
        }
    }
}

// Fermer le tableau
$string .= "</table></div>";
// Ajouyer la pagination
$string .= "
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
// Afficher la base de données de recherche
echo "<h3 class='o-result__m-dataSearch'><span>Donnée recherchée sur ".$connectionDatabase." </span></h3><br>";
// Afficher le reste
echo $string;


// Récupération du temps écoulé depuis le lancement du premier timer
$time_end = microtime(true);
$time = round(($time_end - $time_start) * 1000, 3);

// Sauvegarder la trace dans l'historique (le paramètre à 2 correspond au nom de la règle de QD numéro 3, c'est pour attribuer le bon titre dans l'historique)
saveQDInHistory(round($time/1000, 3), $nbResult.'<br> Identifiant recherché : '.$ndOrVia, 2);

?>

    <script>
        // Script pour afficher le temps pris pour effectuer l'action
        if(document.getElementById('temps_global')){
            let delay = <?php echo $time ?>;            
            document.getElementById('temps_global').textContent = "Temps d'éxécution global : "+(delay/1000).toFixed(3)+" secondes";
            document.getElementById('temps_global').style.display = 'block';
        }

        // JQuery pour gérer le clic sur l'icone de téléchargement pour télécharger le CSV
        $(document).ready(function() {
            // Au clic sur la div de téléchargement
            $('#telechargement').click(function() {

                // Récupérer les données du tableau PHP
                let tableau = <?php echo json_encode($tabqd); ?>;

                let nom_fichier = "<?php echo $name_file; ?>";
                
                // Convertir les données en format CSV
                let csvContent = "data:text/csv;charset=utf-8,";                
                tableau.forEach(function(rowArray) {
                    let row = rowArray.map(function(value) {
                    return '"' + value.replace(/"/g, '""') + '"';
                    }).join(";");
                    
                    csvContent += row + "\r\n";
                });
                
                // Créer un lien de téléchargement
                let encodedUri = encodeURI(csvContent);
                let link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", nom_fichier);
                
                // Simuler un clic sur le lien de téléchargement
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        });

    </script>
<?php
?>

