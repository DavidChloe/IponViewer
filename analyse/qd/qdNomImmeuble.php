<?php

$time_start = microtime(true);

$connectionDatabase = $_POST["database"];

if($connectionDatabase == 'IPON'){
    DatabaseConnectionPool::createConnection('Ipon');
    $pdo = DatabaseConnectionPool::getPDO('Ipon');

    $name_file = "export_qd_nom_immeuble_ipon.csv";
}
else{
    DatabaseConnectionPool::createConnection('IponRip');
    $pdo = DatabaseConnectionPool::getPDO('IponRip');

    $name_file = "export_qd_nom_immeuble_iponrip.csv";
}

ini_set('max_execution_time', 300); // Augmente la limite à 300 secondes (5 minutes)

// Récupère la limite max de résultats attendus 
$num_fetch_row = $_POST['num_fetch_row'];

// Initialiser la variable numérique du nombre de résultats obtenues
$nbResult = 0;


$req = requeteNomImmeuble($num_fetch_row);
$prep = $pdo->prepare($req);
$prep->execute();
$res = $prep->fetchAll(PDO::FETCH_ASSOC);


if (!empty($res)) {

    $nbResult = count($res);

    $string = "<div class='p-search__result'>";


    if(count($res) > 5 ){
        //affichage du menu déroulant pour le choix du nombre de ligne à afficher
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
    }


    $string.= "<table class='o-result p-search__result pagination' data-max-page='15'>";
    $string.= " <tr>
                    <th colspan='3' class='o-result__column'>
                        <p id='telechargement' style='cursor: pointer;'>
                            <img src='../public/images/download.png' alt='Download' width='16px'><br/>
                            Extraction au Format .csv
                        </a>
                        </p>
                    </th>
                </tr>
                <tr class='o-result__row__title'>
                    <th class='o-result__column'><b>Nom</b></th>
                    <th class='o-result__column'><b>Object ID</b></th>
                    <th class='o-result__column'><b>Parent ID</b></th>
                </tr>";
        //variable pour l'export QD
        $tabqd = [];
        $tabqd[] = ['NOM', 'OBJECT ID', 'PARENT ID'];

    foreach ($res as $value) {
        $string.= "
                <tr class='o-result__row'>
                    <td class='o-result__column'>{$value['NAME']}</td>
                    <td class='o-result__column'>{$value['OBJECT_ID']}</td>
                    <td class='o-result__column'>{$value['PARENT_ID']}</td>
                </tr>";
    
        //Intégration des values dans le tableau tabqd pour l'extraction
        $tabqd[] = [$value['NAME'],$value['OBJECT_ID'], $value['PARENT_ID']];
    }

    $string .= "</table></div>";
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

    
}
echo "<h3 class='o-result__m-dataSearch'><span>Donnée recherchée sur ".$connectionDatabase." </span></h3><br>";
echo $string;


// Récupération du temps écoulé depuis le lancement du premier timer
$time_end = microtime(true);
$time = round(($time_end - $time_start) * 1000, 3);


// Sauvegarder la trace dans l'historique (le paramètre à 1 correspond au nom de la règle de QD numéro 2, c'est pour attribuer le bon titre dans l'historique)
saveQDInHistory(round($time/1000, 3), $nbResult, 1);

?>
    <script>
        if(document.getElementById('temps_global')){
            let delay = <?php echo $time ?>;            
            document.getElementById('temps_global').textContent = "Temps d'éxécution global : "+(delay/1000).toFixed(3)+" secondes";
            document.getElementById('temps_global').style.display = 'block';
        }


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