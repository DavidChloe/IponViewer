<?php

// FICHIER APPELE DANS : header.php, iponViewerInstaller.php, qdResults.php

// FONCTION UTILISEE DANS : test_connection_database.php, compareSoftwareVersion.php

// DESCRITPION :

/*

    Cette fonction a pour but d'enregistrer dans un fichier texte de sauvegarde une information liée à une clé unique.
    Elle recherche un fichier texte à un emplacement spécifique du projet. Elle créée le fichier s'il n'existe pas.
    Si la clef n'existe pas, la paire clef-valeur est ajouté au fichier.
    Si la clef existe, l'ancienne valeur et la nouvelle sont comparées. Dans le cas d'une différence, la nouvelle valeur prend la place de l'ancienne.
    La sauvegarde d'un élément suit la norme suivante :

        CLEF_EN_UNE_SEULE_CHAINE : VALEUR_EN_UNE_SEULE_CHAINE\n

    où '\n' correspond à un retour à la ligne

*/


// ARGUMENTS

/*
    $key : 
        type : chaine de caractères
        descritption : fournit la clef qui doit être unique 

    $data : 
        type : chaine de caractères
        description : contient la valeur à sauvegarder dans le fichier
*/


// RETOUR : 

/*
    type : Booléen
    description : un retour à true signifie que l'enregistrement a été fait alors qu'un retour à flase signifie que l'enregistrement ne l'a pas été
*/



function setDataInSaveFile($key, $data){

    // Définir le chemin absolu du dossier parent
    $cheminParent = realpath(__DIR__.'/../public/data/');

    // Définir le chemin absolu du fichier 
    $cheminFichier = $cheminParent . "/saveDateCheck.txt";

    // Récupération de la clé unique
    $refCode = $key;

    // Si le fichier n'existe pas 
    if (!file_exists($cheminFichier)) {

        // Création du contenu 
        $line = $refCode." : ".$data;
        
        // Ecriture dans le fichier
        file_put_contents($cheminFichier, $line);  

        // Renvoie true pour une sauvegarde réussie
        return true;

    } else {

        // Si le fichier existe et n'est pas vide
        if(filesize($cheminFichier) != 0){

            // Récupération du contenu dans un tableau : une ligne = une case du tableau
            $lines = array_map('trim', file($cheminFichier));

            // Initialisation d'un flag à false
            $refTrouve = false;

            // Trouver la ligne correspondant à refCode (= CUID)
            foreach ($lines as $key => $line) {

                // Si refCode existe dans le fichier
                if (strpos($line, $refCode) === 0) {

                    // On récupère la version de sa dernière installation
                    $current_data = trim(substr($line, strpos($line, ':')+2));
                    $current_data = explode(' ', $current_data);
                    $current_data = $current_data[0];

                    // On met le flag à true
                    $refTrouve = true;

                    // Comparer les deux versions
                    if ($data != $current_data) {
                        // Mettre à jour le numéro de version et la date
                        $lines[$key] = str_replace(substr($line, strpos($line, ':')+1), ' '.$data, $line);
                        // Écrire les modifications dans le fichier texte
                        file_put_contents($cheminFichier, implode("\n", $lines));

                        // Renvoie true pour une sauvegarde réussie
                        return true;
                    }
                    else{
                        
                        // Renvoie false car pour cette clef la valeur présente est la même que la nouvelle
                        return false;
                    }

                }
            }

            // Si on a pas trouvé l'refCode dans le fichier
            if(!$refTrouve){

                // On rajoute l'refCode avec la version et la date dans le fichier
                array_push($lines, $refCode." : ".$data);
                file_put_contents($cheminFichier, implode("\n", $lines));

                return true;
            }

            
        }
        // Sinon si le fichier existe mais est vide
        else{

            // On créé le contenu 
            $line = $refCode." : ".$data;

            // On écrit le contenu dans le fichier
            file_put_contents($cheminFichier, $line);

            return true;
        }

    }
}




?>