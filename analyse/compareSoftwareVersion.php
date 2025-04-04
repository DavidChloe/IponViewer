

<?php




    // URL du dossier communs à tous   

    $dir = TEAMS_PATH_REPOSITORY;
    
    if(is_dir($dir)){

     
        // On récupère dans un tableau les noms de tous les dossiers au chemin passé à la fonction
        $array = scandir($dir);

        //print_r2($array);

        // Si on a des résultats
        if($array){

            // On récupère la case du tableau contenant 
            $matches = preg_grep('/^Ipon Viewer/', $array);

            // On vérifie qu'on a bien un seul résultats car il ne doit y avoir qu'une seule dernière version
            if(count($matches) == 1){

                // On réindexe les clefs du tableau car on veut que notre nom de dossier récupéré ait la clef 0
                $matches = array_values($matches);

                // On divise le nom du dossier par rapport au caractère " " (= un espacement)
                $tab = explode(" ", $matches[0]);

                // On définit une variable avec un numéro de version par défaut 
                $lastVersion = "V0.0.0";

                // Si notre nom de dossier est au bon format, c'est à dire "Ipon Viewer NuméroDeVersion"
                if(count($tab) > 2){
                    // On récupère uniquement le numéro de version (VX.X.X)
                    $lastVersion = $tab[2];
                }
            
                // On initialise un flag à false
                $flag = false;
                

                setDataInSaveFile('version_actuelle', $version);
                setDataInSaveFile('version_disponible', $lastVersion);

            
                // On utilise la fonction de comparaison des version fournit par PHP
                if(version_compare($version, $lastVersion) >= 0){
                    // Si notre version actuellement possédée est la dernière (ou la seule accessible car il se peut que le dossier dans le répertoire OneDrive ne soit pas nommé de la bonne manière),
                    // On met notre flag à false
                    //echo "Vous êtes à jour.";
                    $flag = false;
                }
                else{
                    // Sinon, ça veut dire qu'une nouvelle version a été publiée et est accessible
                    // On met alors notre flag à true
                    //echo "Nouvelle version disponible !";
                    $flag = true;
                }
                

                // On envoie alors la valeur du flag et le numério de la sernière version à notre script javascript pour gérer l'affichage
                $jsonUpdate = json_encode(["update"=>$flag]);
                $jsonVersion = json_encode(["lastVersion"=>$lastVersion]);



        ?>

        <script>


            // Récupérer les tableaux php dans le javascript
            let update = <?php echo $jsonUpdate; ?>;
            let lastVersion = <?php echo $jsonVersion; ?>;
            
            // console.log(update);
            // console.log(lastVersion);
            
            // on initialise notre message par défaut
            let nouvelleVersion = 'Nouvelle version';

            // Si on a bien reçu le numéro de version, alors on change le message
            if(lastVersion.length !=0 ){
                nouvelleVersion = lastVersion['lastVersion'];
            }
            
            // Si on a bien reçu le flag
            if(Object.keys(update).length != 0){

                // On récupère notre élément d'affichage html
                let bandeauUpdate = document.getElementById('updateAvailable');

                // Si on l'a bien récupéré
                if(bandeauUpdate != null){

                    // On récupère la valeur du flag
                    for (const key in update) {

                        // S'il est true
                        if (update[key] === true) {
                            // On fait apparaitre notre éléméent html, on complète notre message et on les affiche
                            bandeauUpdate.style.display = 'block';
                            bandeauUpdate.innerHTML = nouvelleVersion+' disponible !';
                        }
                        else{
                            // Sinon on n'affiche pas notre élément  
                            bandeauUpdate.style.display = 'none';
                        }
                    }
                }
                
            }


        </script>

        <?php 

        // Fermer les if
                }
            
            }
    }

?>

