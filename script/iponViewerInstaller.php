

<!-- 
    Page pour la mise à jour automatique d'Ipon Viewer
-->

<!DOCTYPE html>
<html>

<head>
    <!-- En-tête de la page -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>
        Installation nouvelle version 
    </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../public/css/styleInstaller.css" />
</head>

<body id="body_installer">


<?php
    include '../script/saveKeyData.php';
    include '../script/getSaveData.php';
    include '../config/variables_globales.php';
?>

<section class="global_container">

    <section class="card_installer">
        <h1>Installation d'Ipon Viewer - Consignes d'installation</h1>

        <!-- 
            Prérequis d'installation
        -->
        <article class="card_installer_warning_container">
            <h4>• Prérequis :</h4>
            <p>
                L'installation nécessite la synchronisation des fichiers du répertoire '<a href='https://orange0.sharepoint.com/:f:/r/sites/AmliorationContinue_Domaine_Reseau/Documents%20partages/Ecosyst%C3%A8me_Conception'>Ecosystème_Conception</a>' de l'équipe Teams 'Echange_DomaineRéseau' avec votre répertoire 'orange.com' sur votre PC.
                <br><br>
                Veuillez vous assurer que ce répertoire est bien synchroniser sur votre appareil.
                <br><br>
                Dans le cas où ce répertoire ne serez pas accessible, rapprochez vous de votre manager.
                <br><br>
                Ipon Viewer est un logiciel qui s'execute sur un serveur Apache en local. Assurez-vous de posséder le logiciel Xampp correctement configurer et de version minimale 3.3.0 .
                <br><br>
                Assurez-vous également que le nom de votre dossier d'installation commence bien par 'xampp' et que ce répertoire se trouve bien dans le répertoire 'C:/My Program Files', autrement l'installation automatique échouera.


            </p>
        </article>

        <!-- 
            Consignes d'installation manuelle
        -->

        <article class="card_installer_rule_container">

            <h4>• Pour installation manuelle :</h4>

            <ol class="container_list">
                <li> <span>ALLER</span> sur votre poste dans votre explorateur de fichiers dans 'orange.com > Echange_DomaineRéseau - Ecosystème_Conception > IPON Viewer' ou dans 'C:/ > orange.com > IPON Viewer'</li>
                <li> <span>COPIER</span> le dossier intitulé 'Ipon Viewer VXX.XX.XX' ( X représente le numéro de version )</li>
                <li> <span>ALLER</span> dans 'C:/' et <span>TROUVER</span> le dossier d'installation de xampp dans 'My Program Files'</li>
                <li> <span>ALLER</span> dans ce dossier</li>
                <li> <span>ALLER</span> dans 'htdocs > html' (si vous n'avez pas de répertoire 'html' dans 'htdocs' le <span>CRÉER</span>) </li>
                <li> Si vous possédez déjà une version d'Ipon Viewer, <span>SUPPRIMER</span> le dossier déjà présent ou <span>DÉPLACER</span> le dossier dans un autre répertoire</li>
                <li> <span>COLLER</span> le dossier contenant la nouvelle version et le <span>RENOMMER<span> 'Ipon Viewer'</li>
                <li> Si votre serveur Apache n'est pas démarré, <span>OUVRIR</span> la panneau de contrôle xampp et <span>CLIQUER</span> sur 'Start' au niveau du label 'Apache'</li>
                <li> <span>OUVRIR</span> un navigateur internet</li>
                <li> Dans la barre de recherche, <span>TAPER</span> 'localhost:8082/html/'</li>
                <li> <span>CLIQUER</span> sur le nom de la nouvelle version d'Ipon Viewer</li>
                <li> Vous avez maintenant accès à la dernière version d'Ipon Viewer</li>

            </ol>

        </article>

        <!-- 
            Bouton d'installation automatique
        -->
        <article>
            <h4>• Pour installation automatique :</h4>
            <form action="" method="post" class="form_installer">
                
                <input type="submit" name="submit_installer" value="INSTALLER">
            </form>
        </article>



    </section>
</section>

    
<?php


if (isset($_POST['submit_installer'])) {

    $rapport = array();

    $lastVersion = getDataFromSaveFile('version_disponible');
    $version = getDataFromSaveFile('version_actuelle');

    
    $source = TEAMS_PATH_REPOSITORY;


    if(is_dir($source)){
        $iponViewerFolders = scandir($source);
        $source = $source.'/Ipon Viewer '.$lastVersion;
    }



    if(is_dir($source)){


        $destination = REPERTOIRE_IPON_VIEWER;

        if(is_dir($destination)){
            
            // function recursiveDelete($directory) {
            //     if (!is_dir($directory)) {
            //         return;
            //     }
            
            //     $files = glob($directory . '/*');
            //     foreach ($files as $file) {
            //         if (is_dir($file)) {
            //             recursiveDelete($file);
            //         } else {
            //             unlink($file);
            //         }
            //     }
            
            //     rmdir($directory);
            // }


            function recursiveCopy($source, $destination) {

                // 

                // Ouvrir le dossier source
                $dir = opendir($source);

                // echo "La source : ".$source;
                // echo "<br>La destination : ".$destination;

                // Parcourir tous les fichiers et dossiers du dossier source
                while (false !== ($file = readdir($dir))) {
                    if (($file != '.') && ($file != '..')) {
                        $sourceFile = $source . '/' . $file;
                        $destinationFile = $destination . '/' . $file;

                        
                        if (is_dir($sourceFile)) {
                            // Récursivement copier les sous-dossiers
                            recursiveCopy($sourceFile, $destinationFile);
                        } else {
                            // Copier les fichiers individuels
                            copy($sourceFile, $destinationFile);
                        }
                    }
                }

                // Fermer le dossier source
                closedir($dir);

                return ['true', 'Copie des dossiers et fichiers effectuée.'];
            }


            // Appeler la fonction recursiveCopy pour copier les fichiers et dossiers
            $rapport[] = recursiveCopy($source, $destination);

            
        }
        else{
            $rapport[] = ['false', 'Impossible d\'accéder au dossier "htdocs > html" dans votre dossier d\'installation xampp : '.$destination.'.'];
        }
        
    }
    else{
        $rapport[] = ['false', 'Le dossier source n\'est pas accessible. Vérifier que vous avez bien synchronisé le répertoire Teams sur votre OneDrivre et que vous avez accès à la dernière version.'];
    }

    $affichageRapport = '<div class="alerte">
    <section class="alerte_popup">
        <h1>Rapport d\'installation</h1>
        <ul class="alerte_popup_rapport">';

    $flag = true;

    foreach ($rapport as $key => $value) {
        // echo $value[0];
        // echo $value[1];
        // echo "<br><br>";
        if($value[0] == 'false'){
            $flag = false;
        }

        $affichageRapport .= "<li>$value[1]</li>";
    }

    if($flag){
        $affichageRapport .= "<li>Installation terminée avec succès</li>";
    }
    else{
        $affichageRapport .= "<li>Un problème est survenu lors de l'installation</li>";
    }

    $affichageRapport .= '</ul>

    <p>Installation effectuée dans : '.$destination.'</p>

    <div class="retour_application"><span><a href="../index.php">Cliquez pour revenir à l\'accueil</a></span></div>
</section>
</div>';

    echo $affichageRapport;

?>

<script></script>

<?php
  
}

?>

</body>

</html>




