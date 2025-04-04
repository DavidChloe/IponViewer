<?php


// FICHIER APPELE DANS : ControllerHeader.php

// FONCTION UTILISEE DANS : ControllerHeader.php, _base.php, searchModeByID.php, searchModeNDVIA.php, searchModePTGER.php, searchModeSupportGER.php

// DESCRIPTION :

/*

    Cette fonction permet de sauvegarder le résultat d'une recherche/action dans l'historique de l'utilisateur.
    La recherche ou l'action est ajoutée aux recherche de la journée. Elle comprend l'heure, le mode de recherche sélectionné et la référence passée en entrée si c'était une recherche, et le résumé.

*/

// ARGUMENTS :

/*

    $resultatRecherche :
        type : chaine de caractères
        description : contient les informations résumant la recherche ou l'action qu'on souhaite sauvegarder.

*/

// RETOUR : 

/*

    type : Void

*/


function saveSearchResultInHistory($resultatRecherche){

    if($resultatRecherche == "" || $resultatRecherche == null){
        $resultatRecherche = "";
    }

    // Récupérer l'heure
    $heureActuelle = date("H:i:s");
    
    // Récupérer le chemin d'accès vers les logs sur Teams
    $cheminFichier = TEAMS_PATH_LOGS;
    
    $findFolder = false;

    if(is_dir($cheminFichier)){
        $findFolder = true;
    }

    // Si le dossier cible existe
    if($findFolder){

        // Récupérer le mode de recherche
        $modeRecherche = $_POST['typeSearch'];

        // Traduire le mode de recherche sélectionner
        switch ($modeRecherche) {
            case 'search-byNDVIA':
                $modeRecherche = "Recherche par ND-VIA";
                break;
            case 'search-byID':
                $modeRecherche = "Recherche par ID";
                break;
            case 'search-perso':
                $modeRecherche = "Recherche personnalisée";
                break;
            case 'search-GER':
                $modeRecherche = "Recherche par PT-GER - ".$_POST['database']."";
                break;        
            case 'search-supportGER':
                $modeRecherche = "Recherche par support GER";

                switch($_POST['supportGerTypeSearch'])
                {
                    case 'gerTypeSearch-AppuiFt':
                        $modeRecherche .= " - Appui FT";
                    break;
                    case 'gerTypeSearch-AppuiErdf':
                        $modeRecherche .= " - Appui ERDF";
                    break;
                    case 'gerTypeSearch-Chambre':
                        $modeRecherche .= " - Chambre";
                    break;
                    case 'gerTypeSearch-Armoire':
                        $modeRecherche .= " - Armoire";
                    break;
                    case 'gerTypeSearch-Immeuble':
                        $modeRecherche .= " - Immeuble";
                    break;
                    default :
                        $modeRecherche .= " - Type support inconnu";
                    break;
                }

                break;
            
            default:
                $modeRecherche = "Pas de mode de recherche";
                break;
        }


        // Récupérer le contenu de la recherche
        $contenuRecherche = $_POST['searchBar'];

        if($contenuRecherche == null){
            $contenuRecherche = "Recherche vide";
        }

        // Si un numéro de PT a été renseigné on le récupère
        // if(isset($_POST['supportGerRefPT']))
        // {
        //     $numeroPT = $_POST['supportGerRefPT'];
        //     if(!empty($numeroPT)){
        //         $contenuRecherche .= ' - '.$numeroPT;
        //     }
        // }

        

        
        // Nom du fichier de sauvegarde des logs de l'utilisateur
        // Ce nom est construit comme suit : CUID_logs.json
        $nomFichier = "".get_current_user()."_logs.json";
        

        // Définir le chemin absolu du fichier 
        //$cheminFichier = $cheminFichier."/".$nomFichier;
        $cheminFichier = $nomFichier;


        // Vérifier si le fichier existe 
        if (!file_exists($cheminFichier)) {

            // S'il n'existe pas, on le créé (il n'a pas encore de contenu, d'où le null)
            file_put_contents($cheminFichier, null);

            // On lui insère des données
            // La clé du tableau sera la date du jour. Il contient l'heure de la recherche, le mode de recherche sélectionné et le contenu de la recherche
            $dataD = array(date("Y-m-d") => array([$heureActuelle, $modeRecherche, $contenuRecherche, $resultatRecherche]));
            // On ajoute notre nouveau tableau à un taleau vide, il sera donc au rend 0. Ce sera le premier objet du fichier nouvellement créé.
            $data[] = $dataD;
            // On écrit dans notre fichier au format JSON
            file_put_contents($cheminFichier, json_encode($data));


        } else {

            // Si le fichier existe, le lire
            // $data récupère un tableau contenant les données du fichier JSON transformé en tableau de données php
            $data = json_decode(file_get_contents($cheminFichier), true);

            
            // Vérifier si le fichier contient des données
            if (empty($data)) {
                // Si le fichier est vide, ajouter un tableau avec les données par défaut
                // Ca correspond aux mêmes étapes que pour un fichier inexistant
                $dataD = array(date("Y-m-d") => array([$heureActuelle, $modeRecherche, $contenuRecherche, $resultatRecherche]));
                $data[] = $dataD;
                file_put_contents($cheminFichier, json_encode($data));
            }
            else{
                // Si le fichier contient des données

                // On récupère les clés correspondant au dernier objet du tableau $data et du dernier objet contenu dans cette dernière case (à noter que ce dernier objet est en réalité le seul dans cette case et que sa clé correspond à une date)
                
                $firstStepLastKey = array_key_last($data); //correspond au numéro de la dernière clé au premier niveau 
                $secondStepLastKey = array_key_last($data[$firstStepLastKey]);//correspond au numéro de la dernière clé au deuxieme niveau à la derniere clé du premier niveau 
                
                $dateActuelle = date("Y-m-d");

                // Si la dernière dates à laquelle des données ont été recherchées est la même que la date du jour
                if($dateActuelle == $secondStepLastKey){
                    

                    // On met dans un tableau l'heure le mode de recherche et le contenu de la recherche
                    $newArray = [$heureActuelle, $modeRecherche, $contenuRecherche, $resultatRecherche];

                    // On ajoute ce tableau dans celui dont la clé correspond à la date du jour (qui existe déjà puisque des recherches ont déjà été faites à cette date)
                    $data[$firstStepLastKey][$secondStepLastKey][] = $newArray;

                    // On modifie le fichier (ou plutôt on le réécrit) avec les anciennes informations auxquelles on a ajouté les nouvelles données
                    file_put_contents($cheminFichier, json_encode($data));
                }
                else{
                    // Dans le cas où la date de la dernière recherche ne correspond pas à la date du jour (ce qui signifie que nous sommes X jours après la dernière recherche)
                    // On récupère la dernière clé du tableau global $data (correspond à une donnée numérique ex: 0 ou 1 ou 2, etc... et non pas une date)
                    
                    //$firstStepLastKeyHighTable = array_key_last($data);

                    // Je mets dans un nouveau tableau mes données dont la clé est la date du jour
                    $dataT = array($dateActuelle => array([$heureActuelle, $modeRecherche, $contenuRecherche, $resultatRecherche]));

                    // Equivaut à array_push(), on ajoute dans un tableau, $data et $dataT
                    // Au lieu de mettre notre nouveau tableau dans le même que le précédent, on l'isole dans une nouvelle case qui correspond au rend de la dernière clé + 1
                    $data[$firstStepLastKey+1] = $dataT;

                    // On réécrit le fichier JSOn avec nos modifications
                    file_put_contents($cheminFichier, json_encode($data));

                }
            }            
        }
    }
    else{
        echo "<div class='o-alert'>Votre recherche ne peut pas être sauvegardée dans les logs : fichier de logs inaccessible.</div>";
    }
    
}





function saveQDInHistory($time, $count, $index){

    // Récupérer l'heure
    $heureActuelle = date("H:i:s");
    
    // Récupérer le chemin d'accès vers les logs sur Teams
    $cheminFichier = TEAMS_PATH_LOGS;
    
    $findFolder = false;

    if(is_dir($cheminFichier)){
        $findFolder = true;
    }

    // Si le dossier cible existe
    if($findFolder){

        // Récupérer les titre de recherche de QD
        $titreQD = $_POST['title'];
        
        // Récupérer celui correspondant au mode lancé
        $titreQD = $titreQD[$index];

        // Récupérer la base de données sélectionnée
        $base = $_POST['database'];
        
        // Récupérer le nombre de recherche souhaités
        $numFetch = $_POST['num_fetch_row'];

        $resumeQD = "<br>Sur base de données $base <br> Nombre de résultats souhaité : ";
        
        if($numFetch == 0 || $numFetch == null){
            $resumeQD .= "pas de limite fixée"; 
        }
        else{
            $resumeQD .= "$numFetch";
        }
        $resumeQD .= "<br> Nombre de résultats obtenus : $count";
        $resumeQD .= "<br> Durée : $time s.";
        

        // Récupérer le contenu de la recherche
        $contenuRecherche = "";

            
        // Nom du fichier de sauvegarde des logs de l'utilisateur
        // Ce nom est construit comme suit : CUID_logs.json
        $nomFichier = "".get_current_user()."_logs.json";
        

        // Définir le chemin absolu du fichier 
        $cheminFichier = $cheminFichier."/".$nomFichier;

        // Vérifier si le fichier existe 
        if (!file_exists($cheminFichier)) {

            // S'il n'existe pas, on le créé (il n'a pas encore de contenu, d'où le null)
            file_put_contents($cheminFichier, null);

            // On lui insère des données
            // La clé du tableau sera la date du jour. Il contient l'heure de la recherche, le mode de recherche sélectionné et le contenu de la recherche
            $dataD = array(date("Y-m-d") => array([$heureActuelle, $titreQD, $contenuRecherche, $resumeQD]));
            // On ajoute notre nouveau tableau à un taleau vide, il sera donc au rend 0. Ce sera le premier objet du fichier nouvellement créé.
            $data[] = $dataD;
            // On écrit dans notre fichier au format JSON
            file_put_contents($cheminFichier, json_encode($data));


        } else {

            // Si le fichier existe, le lire
            // $data récupère un tableau contenant les données du fichier JSON transformé en tableau de données php
            $data = json_decode(file_get_contents($cheminFichier), true);

            
            // Vérifier si le fichier contient des données
            if (empty($data)) {
                // Si le fichier est vide, ajouter un tableau avec les données par défaut
                // Ca correspond aux mêmes étapes que pour un fichier inexistant
                $dataD = array(date("Y-m-d") => array([$heureActuelle, $titreQD, $contenuRecherche, $resumeQD]));
                $data[] = $dataD;
                file_put_contents($cheminFichier, json_encode($data));
            }
            else{
                // Si le fichier contient des données

                // On récupère les clés correspondant au dernier objet du tableau $data et du dernier objet contenu dans cette dernière case (à noter que ce dernier objet est en réalité le seul dans cette case et que sa clé correspond à une date)
                
                $firstStepLastKey = array_key_last($data); //correspond au numéro de la dernière clé au premier niveau 
                $secondStepLastKey = array_key_last($data[$firstStepLastKey]);//correspond au numéro de la dernière clé au deuxieme niveau à la derniere clé du premier niveau 
                
                $dateActuelle = date("Y-m-d");

                // Si la dernière dates à laquelle des données ont été recherchées est la même que la date du jour
                if($dateActuelle == $secondStepLastKey){
                    

                    // On met dans un tableau l'heure le mode de recherche et le contenu de la recherche
                    $newArray = [$heureActuelle, $titreQD, $contenuRecherche, $resumeQD];

                    // On ajoute ce tableau dans celui dont la clé correspond à la date du jour (qui existe déjà puisque des recherches ont déjà été faites à cette date)
                    $data[$firstStepLastKey][$secondStepLastKey][] = $newArray;

                    // On modifie le fichier (ou plutôt on le réécrit) avec les anciennes informations auxquelles on a ajouté les nouvelles données
                    file_put_contents($cheminFichier, json_encode($data));
                }
                else{
                    // Dans le cas où la date de la dernière recherche ne correspond pas à la date du jour (ce qui signifie que nous sommes X jours après la dernière recherche)
                    // On récupère la dernière clé du tableau global $data (correspond à une donnée numérique ex: 0 ou 1 ou 2, etc... et non pas une date)
                    
                    //$firstStepLastKeyHighTable = array_key_last($data);

                    // Je mets dans un nouveau tableau mes données dont la clé est la date du jour
                    $dataT = array($dateActuelle => array([$heureActuelle, $titreQD, $contenuRecherche, $resumeQD]));

                    // Equivaut à array_push(), on ajoute dans un tableau, $data et $dataT
                    // Au lieu de mettre notre nouveau tableau dans le même que le précédent, on l'isole dans une nouvelle case qui correspond au rend de la dernière clé + 1
                    $data[$firstStepLastKey+1] = $dataT;

                    // On réécrit le fichier JSOn avec nos modifications
                    file_put_contents($cheminFichier, json_encode($data));

                }
            }            
        }
    }
    else{
        echo "<div class='o-alert'>Votre recherche ne peut pas être sauvegardée dans les logs : fichier de logs inaccessible.</div>";
    }
    
}

?>