<?php

// Ce code s'éxecute uniquement si on n'eest pas dans l'historique
if(!isset($_POST['historySearch'])){

    // Initialisation d'un flag
    $check = true;
    // Initialisation de la clé servant dans le fichier de sauvagarde interne 
    $refCode = 'date_last_verif_first_connection';
    // récupération de la date actuelle (date à laquelle est éxecutée ce code)
    $current_date = date("d/m/Y");

    // Mise à jour ou ajout de la date pour la clé l'identifiant dans le fichier de sauvegarde interne
    $check = setDataInSaveFile($refCode, $current_date);

    // Si la fonction précédente à pu être réalisée, celà signifie que le test de connexion à toutes les bases de données n'a pas été fait. Ca veut dire qu'aujourd'hui,
    // le test de connexion à toutes les bdd n'a pas été fait, donc on le fait
    // Cette action ne se fait qu'une seule fois, au lancement d'Ipon Viewer pour vérifier que toutes les bases de données sont utilisables et qu'on peut s'y connecter
    if($check){
        
        // créer les connexions à toutes les bases de données
        // On récupère les retours dans des variables
        $connexionIpon = DatabaseConnectionPool::createConnection('Ipon');
        $connexionIponRip = DatabaseConnectionPool::createConnection('IponRip');
        $connexionGeoreso = DatabaseConnectionPool::createConnection('Georeso');
        $connexionGeoresoRip = DatabaseConnectionPool::createConnection('GeoresoRip');

        // Comme il s'agit d'un test, on peux fermer la connexion puisqu'on a récupérer les résultats dans nos variables 
        DatabaseConnectionPool::closeConnection('Ipon');
        DatabaseConnectionPool::closeConnection('IponRip');
        DatabaseConnectionPool::closeConnection('Georeso');
        DatabaseConnectionPool::closeConnection('GeoresoRip');

        // Renvoyer les résultats sous forme de JSON pour être utiliser dans le javascript
        // Chaque tableau récupère une donnée spécifique de chaque variable
        
        // Tableau 1 : récupère true ou flase -> true pour une connexion à la base de données établie et false pour une connexion échouée
        $tabDatabase = ["connexionIpon"=>$connexionIpon[0],"connexionIponRip"=>$connexionIponRip[0],"connexionGeoreso" =>$connexionGeoreso[0],"connexionGeoresoRip"=>$connexionGeoresoRip[0]];
        // Tableau 2 : récupère l'heure du test
        $tabHeureTest = ["heureTestIpon" => $connexionIpon[1], "heureTestIponRip" => $connexionIponRip[1], "heureTestGeoreso" => $connexionGeoreso[1], "heureTestGeoresoRip" => $connexionGeoresoRip[1]];
        // Tableau 2 : récupère le temps d'éxécution pour la création de la connexion
        $tabDelais = ["delaisConnexionIpon"=>$connexionIpon[2],"delaisConnexionIponRip"=>$connexionIponRip[2],"delaisConnexionGeoreso" =>$connexionGeoreso[2],"delaisConnexionGeoresoRip"=>$connexionGeoresoRip[2]];

        // Encodage json pour utilisation dans le script javascript
        $jsonTabDatabase = json_encode($tabDatabase);
        $jsonTabHeureTest = json_encode($tabHeureTest);
        $jsonTabDelais = json_encode($tabDelais);

?>

<script>

    // récupération des résultats du php dans le js
    let data = [
        <?php echo $jsonTabDatabase; ?>,
        <?php echo $jsonTabHeureTest; ?>,
        <?php echo $jsonTabDelais; ?>
    ];

    // Mise à jour des données du localStorage. Cette mise à jour sera interprétée par un autre script qui observe le localStorage et qui change l'affichage pour les voyants de connexion aux base de données.
    data.forEach((item) => {
        if (Object.keys(item).length !== 0) {
            for (const key in item) {
                // La fonction de mise à jour du localStorage utilise la clef passée au tableau et lui associe sa valeur 
                updateLocalStorage(key, item[key]);                
            }
        }
    });

</script>

<?php

    }
}
?>