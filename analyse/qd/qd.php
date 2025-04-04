<?php

// if(isset($_POST['submitQDScan']))
// {

    $time_start = microtime(true);

    $connexionIpon = DatabaseConnectionPool::createConnection('Ipon');
    $connexionIponRip = DatabaseConnectionPool::createConnection('IponRip');
    $connexionGeoreso = DatabaseConnectionPool::createConnection('Georeso');
    $connexionGeoresoRip = DatabaseConnectionPool::createConnection('GeoresoRip');

    // Renvoyer la réponse sous forme de JSON pour être utiliser dans le javascript
    $tabDatabase = ["connexionIpon"=>$connexionIpon[0],"connexionIponRip"=>$connexionIponRip[0],"connexionGeoreso" =>$connexionGeoreso[0],"connexionGeoresoRip"=>$connexionGeoresoRip[0]];

    $tabHeureTest = ["heureTestIpon" => $connexionIpon[1], "heureTestIponRip" => $connexionIponRip[1], "heureTestGeoreso" => $connexionGeoreso[1], "heureTestGeoresoRip" => $connexionGeoresoRip[1]];

    $tabDelais = ["delaisConnexionIpon"=>$connexionIpon[2],"delaisConnexionIponRip"=>$connexionIponRip[2],"delaisConnexionGeoreso" =>$connexionGeoreso[2],"delaisConnexionGeoresoRip"=>$connexionGeoresoRip[2]];


    $jsonTabDatabase = json_encode($tabDatabase);
    $jsonTabHeureTest = json_encode($tabHeureTest);
    $jsonTabDelais = json_encode($tabDelais);

    $num_fetch_row = $_POST['num_fetch_row'];

?>

<script>

// Récupérer le tableau php dans le javascript
let tab = <?php echo $jsonTabDatabase; ?>;
let tabHeure = <?php echo $jsonTabHeureTest; ?>;
let tabDelais = <?php echo $jsonTabDelais; ?>;

// Si notre tableau n'est pas vide
if(Object.keys(tab).length != 0){

    for (const key in tab) {

        // Vérification si la clé existe déjà dans le localStorage
        if(localStorage.getItem(key)) {
        // La clé existe déjà, on met à jour sa valeur
            localStorage.setItem(key, tab[key]);
        } else {
            // La clé n'existe pas, on la crée avec la valeur spécifiée
            localStorage.setItem(key, tab[key]);
        }
    }
}


if(Object.keys(tabHeure).length != 0){

    for (const key in tabHeure) {

        // Vérification si la clé existe déjà dans le localStorage
        if(localStorage.getItem(key)) {
        // La clé existe déjà, on met à jour sa valeur
            localStorage.setItem(key, tabHeure[key]);
        } else {
            // La clé n'existe pas, on la crée avec la valeur spécifiée
            localStorage.setItem(key, tabHeure[key]);
        }
    }
}

if(Object.keys(tabDelais).length != 0){

    for (const key in tabDelais) {

        // Vérification si la clé existe déjà dans le localStorage
        if(localStorage.getItem(key)) {
        // La clé existe déjà, on met à jour sa valeur
            localStorage.setItem(key, tabDelais[key]);
        } else {
            // La clé n'existe pas, on la crée avec la valeur spécifiée
            localStorage.setItem(key, tabDelais[key]);
        }
    }
}

</script>

<?php

    $nbResult = 0;

    if($connexionIpon[0] == true && $connexionIponRip[0] == true && $connexionGeoreso[0] == true && $connexionGeoresoRip[0] == true){

    
        $connectionDatabase = null ;
        if ($_POST["database"] == "IPON") {
            $bdd = DatabaseConnectionPool::getPDO('Ipon');
            // include "../config/database_IPON.php";
            $connectionDatabase = "IPON";
            $name_file = "export_qd_ipon.csv";

        } elseif ($_POST["database"] == "RIP") {
            $bdd = DatabaseConnectionPool::getPDO('IponRip');
            // include "../config/database_RIP.php";
            $connectionDatabase = "RIP";
            $name_file = "export_qd_iponrip.csv";

        }

        $objectTypeId = 7070550974112052460;

        $requestLignes = "SELECT name, count(*) AS nb_rows FROM RRE_AT GROUP BY NAME HAVING count(*) >1  ORDER BY nb_rows DESC";
        
        if($num_fetch_row > 0 && $num_fetch_row != null){
            $requestLignes .= " FETCH FIRST $num_fetch_row ROWS ONLY";
        }

        $prepareRequestLignes = $bdd->prepare($requestLignes);
        $prepareRequestLignes->execute();
        $requestLignes = $prepareRequestLignes->fetchAll(PDO::FETCH_ASSOC);

        

        if (!empty($requestLignes)) {

            $nbResult = count($requestLignes);

            $string = "<div class='p-search__result'>";



            if(count($requestLignes) > 5 ){
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
                            <th colspan='2' class='o-result__column'>
                                <p id='telechargement' style='cursor: pointer;'>
                                    <img src='../public/images/download.png' alt='Download' width='16px'><br/>
                                    Extraction au Format .csv
                                
                                </p>
                            </th>
                        </tr>
                        <tr class='o-result__row__title'>
                            <th class='o-result__column'><b>Nom</b></th>
                            <th class='o-result__column'><b>Nombre occurence</b></th>
                        </tr>";
                //variable pour l'export QD
                $tabqd = [];
                $tabqd[] = ['NOM', 'NOMBRE OCCURENCE'];

            foreach ($requestLignes as $value) {
                $string.= "
                        <tr class='o-result__row'>
                            <td class='o-result__column'>{$value['NAME']}</td>
                            <td class='o-result__column'>{$value['NB_ROWS']}</td>
                        </tr>";
            
                //Intégration des values dans le tableau tabqd pour l'extraction
                $tabqd[] = [$value['NAME'],$value['NB_ROWS']];
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
        
       
    }
    else{
        echo '<div class="o-alert">Extraction QD impossible : la connexion à une ou plusieurs bases de données n\'est pas possible.</div>';
    }

    // Récupération du temps écoulé depuis le lancement du premier timer
    $time_end = microtime(true);
    $time = round(($time_end - $time_start) * 1000, 3);

    saveQDInHistory(round($time/1000, 3), $nbResult, 0);

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
                    if(value == null){
                        value = "";
                    }
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