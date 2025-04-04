<?php

// FICHIER APPELE DANS : header.php

// CONTENU UTILISEE DANS : header.php

// DESCRIPTION : 

/*  
    Similaire à la fonction setDataInSaveFile(). Ce code sauvegarde dans un fichier texte la version d'Ipon Viewer.
    Ce fichier ce trouve sur Teams et permet de suivre les versions possédées par les utilisateurs.
    La sauvegarde suit la norme suivante :

        CUID_DE_L'UTILISATEUR : VXX.XX.XX installée le DD/MM/AAAA hh:mm:ss \n

    où '\n' correspond à un retour à la ligne

*/




// Récupération du chemin depuis les variables globales
$cheminFichier = TEAMS_PATH_SUIVI_INSTALLATION;

// Si le fichier existe à l'emplacement définit
if(file_exists($cheminFichier)){

    // Récupérer le CUID de l'utilisateur
    $individu = get_current_user();
    // Récupérer la version établie dans l'index.php
    $new_version = trim($version);
    // Récupérerla date + heure actuelle au format JJ/MM/AAAA hh:mm:ss
    $new_date = date("d/m/Y H:i:s");

    // Vérifier si le fichier existe 
    // S'il n'existe pas on le créé
    if (!file_exists($cheminFichier)) {

        // Création du contenu 
        $line = $individu." : ".$new_version." installée le ".$new_date;
        
        // Ecriture dans le fichier
        file_put_contents($cheminFichier, $line);  

    } else {

        // Si le fichier existe et n'est pas vide
        if(filesize($cheminFichier) != 0){
        
            // Individu à rechercher et à mettre à jour
            $individu = get_current_user();            

            // Récupération du contenu dans un tableau : une ligne = une case du tableau
            $lines = array_map('trim', file($cheminFichier));

            // Initialisation d'un flag à false
            $individuTrouve = false;

            // Trouver la ligne correspondant à l'individu (= CUID)
            foreach ($lines as $key => $line) {

                // Si l'individu existe dans le fichier
                if (strpos($line, $individu) === 0) {

                    // On récupère la version de sa dernière installation
                    $current_version = trim(substr($line, strpos($line, ':')+2));
                    $current_version = explode(' ', $current_version);
                    $current_version = $current_version[0];

                    // On met le flag à true
                    $individuTrouve = true;

                    // Comparer les deux versions
                    if ($new_version != $current_version) {
                        // Mettre à jour le numéro de version et la date
                        $lines[$key] = str_replace(substr($line, strpos($line, ':')+1), ' '.$new_version.' installée le '.$new_date, $line);
                        // Écrire les modifications dans le fichier texte
                        file_put_contents($cheminFichier, implode("\n", $lines));

                        break;
                    }
                    

                }
            }

            // Si on a pas trouvé l'individu dans le fichier
            if(!$individuTrouve){
                
                // On rajoute l'individu avec la version et la date dans le fichier
                array_push($lines, $individu." : ".$version." installée le ".$new_date);
                file_put_contents($cheminFichier, implode("\n", $lines));
            }

            
        }
        // Sinon si le fichier existe mais est vide
        else{

            // On créé le contenu 
            $line = $individu." : ".$version." installée le ".$new_date;

            // On écrit le contenu dans le fichier
            file_put_contents($cheminFichier, $line);
        }

    }
}




?>