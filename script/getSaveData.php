
<?php

// FICHIER APPELE DANS : header.php, iponViewerInstaller.php, qdResults.php

// FONCTION UTILISEE DANS : qdResults.php, iponViewerinstaller.php

// DESCRIPTION :

/*

    Cette fonction permet de récupérer la données associée à la clé spécifiée dans le fichier de sauvegarde.

*/

// ARGUMENTS :

/*

    $key :
        type : chaine de caractère

*/

// RETOUR :

/*

    type : chaine de caractères
    description : contient la valeur correspondant à la clé

*/

function getDataFromSaveFile($key){

    // Définir le chemin absolu du dossier parent
    $cheminParent = realpath(__DIR__.'/../public/data/');

    // Définir le chemin absolu du fichier 
    $cheminFichier = $cheminParent . "/saveDateCheck.txt";

    $refCode = $key;

    // Si le fichier existe
    if (file_exists($cheminFichier)) {

        // Si le fichier existe et n'est pas vide
        if(filesize($cheminFichier) != 0){

            // Récupération du contenu dans un tableau : une ligne = une case du tableau
            $lines = array_map('trim', file($cheminFichier));


            // Trouver la ligne correspondant à $refCode
            foreach ($lines as $key => $line) {


                // Si refCode existe dans le fichier
                if (strpos($line, $refCode) === 0) {

                    // On récupère la valeur associée
                    $current_version = trim(substr($line, strpos($line, ':')+2));
                    $current_version = explode(' ', $current_version);
                    $current_version = $current_version[0];

                    // Renvoyer la valeur
                    return $current_version;

                }
            }
            
        }

        return '-2';
    }

    return '-1';
}




?>