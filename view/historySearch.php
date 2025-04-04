<?php 

/* Cette partie est un peu complexe car elle nécessite l'utilisation de plusieurs tableau afin de formater correctement 
les données dans le fichier JSON. Je vous invite à consulter les fichiers json de logs sur Teams où les données sont enregistrées pour mieux
comprendre leur agencement et l'organisation que j'ai mis en place. (les fichiers de logs se trouvent dans Ecosysteme_Conception > Fichiers > IPON Viewer > Suivi des utilisateurs > logs)*/


// Si l'utilisateur a cliqué sur le bouton pour afficher l'historique de ses recherches
if(isset($_POST['historySearch'])){


    // Récupérer l'utilisateur de la session du PC (= CUID)
    $user = get_current_user();
    
    $findFolder = false;

    $cheminFichier = TEAMS_PATH_LOGS;


    $purgeMois = 2;

    if(is_dir($cheminFichier)){
        $findFolder = true;
    }


    if($findFolder){

        $nomFichier = "".get_current_user()."_logs.json";

        // Définir le chemin absolu du fichier 
        $cheminFichier = $cheminFichier."/".$nomFichier;

        // Vérifier si le fichier existe 
        if (!file_exists($cheminFichier)) {

            // Si le fichier n'existe pas alors qu'on veut le consulter, alors on le créé à vide
            file_put_contents($cheminFichier, null);

            // On affiche un message pour signaler que l'historique est vide
            $htmlContent = "<div class='containerGlobal'>
                                <div class='wrapper'>
                                    <div class='containerTitle'>
                                        Historique des recherches de ".$user." sur les $purgeMois derniers mois (purge automatique) : Aucune données
                                    </div>
                                    <a class='boutonRetour' href='./index.php'>
                                        Retour
                                    </a>
                                </div>
                                <div class='grandContainer'>
                                </div>
                            </div>";
            echo $htmlContent;

        } else {

            // Si le fichier existe, le lire
            // On récupère les données du fichier dans un tableau appelé $data
            $data = json_decode(file_get_contents($cheminFichier), true);
            
        
            
            // Si notre fichier contenait des données
            if(!empty($data)){

                // Définir le chemin absolu du dossier parent
                $parentPath = realpath(__DIR__.'/../public/data/');

                // Définir le chemin absolu du fichier 
                $filePath = $parentPath . "/saveDateCheck.txt";

                $check = true;


                $refCode = 'date_last_verif_purge';
                $current_date = date("d/m/Y");


                if (!file_exists($filePath)) {

                    // Création du contenu 
                    $line = $refCode." : ".$current_date;
                    
                    $check = true;

                    // Ecriture dans le fichier
                    file_put_contents($filePath, $line);  

                } else {

                    // Si le fichier existe et n'est pas vide
                    if(filesize($filePath) != 0){

                        // Récupération du contenu dans un tableau : une ligne = une case du tableau
                        $lines = array_map('trim', file($filePath));

                        // Initialisation d'un flag à false
                        $refTrouve = false;

                        // Trouver la ligne correspondant à refCode (= CUID)
                        foreach ($lines as $key => $line) {


                            // Si refCode existe dans le fichier
                            if (strpos($line, $refCode) === 0) {

                                // On récupère la version de sa dernière installation
                                $current_version = trim(substr($line, strpos($line, ':')+2));
                                $current_version = explode(' ', $current_version);
                                $current_version = $current_version[0];

                                //echo $current_version;

                                // On met le flag à true
                                $refTrouve = true;

                                // Comparer les deux versions
                                if ($current_date != $current_version) {
                                    // Mettre à jour le numéro de version et la date
                                    $lines[$key] = str_replace(substr($line, strpos($line, ':')+1), ' '.$current_date, $line);
                                    // Écrire les modifications dans le fichier texte
                                    file_put_contents($filePath, implode("\n", $lines));

                                    $check = true;
                                    //echo "<br> Version mise à jour";

                                    break;
                                }
                                else{
                                    
                                    $check = false;

                                    break;
                                }

                            }
                        }

                        // Si on a pas trouvé l'refCode dans le fichier
                        if(!$refTrouve){

                            //echo "<br> L'refCode n'a pas été trouvé. refCode créé.";
                            $check = true;
                            // On rajoute l'refCode avec la version et la date dans le fichier
                            array_push($lines, $refCode." : ".$current_date);
                            file_put_contents($filePath, implode("\n", $lines));
                        }                        
                    }
                    // Sinon si le fichier existe mais est vide
                    else{

                        // On créé le contenu 
                        $line = $refCode." : ".$current_date;

                        $check = true;

                        // On écrit le contenu dans le fichier
                        file_put_contents($filePath, $line);
                    }
                }

                if($check){
                    
                    $counter = 0;

                    // On récupère la taille du tableau $data (soit le nombre de dates de recherches) 
                    $nbObjectInArray = count(array_keys($data));

                    // On met dans $dateLimite la date qui se situe un mois avant la date actuelle. $dateLimite est un nombre en secondes (voir la doc pour plus d'information : https://www.php.net/manual/fr/function.strtotime.php)
                    $dateLimite = strtotime('-'.$purgeMois.' month');

                    // On fait une première fois le tour des données de $data
                    for($i = 0; $i < $nbObjectInArray; $i++){

                        // On récupère les clés contenu à chaque rang du tableau $data (normalement, il n'y a qu'une seule clé qui correspond à la date de la recherche)
                        $keys = array_keys($data[$i]);

                        // On parcours ces clés (= dates de recherche)
                        foreach($keys as $key){

                            // On convertit la clé en secondes
                            $timestamp = strtotime($key);

                        
                            // Suppression du tableau à la date de recherche si la date est antérieure à la date limite
                            // On supprime donc toutes les données des recherches d'il y a un mois ou plus par rapport à la date actuelle
                            if ($timestamp < $dateLimite) {
                                // unset permet de supprimer une variable ou un tableau (ici un tableau)
                                unset($data[$i][$key]);

                                $counter++;
                            }                  
                        }
                    }

                    $firstStepLastKey = array_key_last($data); //correspond au numéro de la dernière clé au premier niveau 
                    $secondStepLastKey = array_key_last($data[$firstStepLastKey]);//correspond au numéro de la dernière clé au deuxieme niveau à la derniere clé du premier niveau 
                
                    if($secondStepLastKey == date("Y-m-d")){
                        $data[$firstStepLastKey][$secondStepLastKey][] = [date("H:i:s"), "Historique : $counter journée(s) supprimée(s)", "Purge effectuée", ""];
                    }
                    else{
                        $data[$firstStepLastKey] = array(date("Y-m-d") => array([date("H:i:s"), "Historique : $counter journées(s) supprimée(s)", "Purge effectuée", ""]));
                    }

                    
                    
                }

                
                // On rafraichit $data en ne récupérant que les tableaux qui ne sont pas vide donc uniquement les tableaux avec des dates dont les recherches sont inférieurs à un mois par rapport à la date actuelle
                $data = array_filter($data, function($data) {
                    return !empty($data);
                });


                // Retourner le tableau pour avoir les recherches les plus récentes en haut de page
                $data = array_reverse($data);

                
                
                // Réindexation du tableau car les clés ont été décalées
                $data = array_values($data);
                
            
                // Après les modifiactions, il se peut que le nombre d'éléments dans $data ait changé. On récupère donc à nouveau la taille de $data
                $nbObjectInArray = count(array_keys($data));

                // On créé une première partie de notre affichage final. Elle correspond au cadre qui contiendra les liens de navigation vers les recherches d'une date précise
                $htmlBalise = "<div class='containerBalise'>Aller vers : <ul class='baliseListe'>";

                // Ce tableau récupèrera la dates misent au format courant (jour/mois/année)
                $arrayDateTransforme = [];

                // On parcourt de nouveau $data
                for($i = 0; $i < $nbObjectInArray; $i++){
                    // On récupère les clés (= dates de recherches) pour chaque itération
                    $keys = array_keys($data[$i]);

                    //echo "<br>".print_r2($keys);

                    // Pour chaque clé
                    foreach($keys as $key){

                        // On transforme la date au format courant
                        $timestamp = strtotime($key); 
                        $dateTransforme = date('d/m/Y', $timestamp);

                        // On complète l'affichage en mettant un lien vers l'élément dont l'id correspondra à $dateTransforme
                        $htmlBalise .= "<li>Recherche(s) du <a href='#".$dateTransforme."'>".$dateTransforme."</a></li>";
                    }
                    // On ajoute à notre tableau, la date transformée au format courant 
                    $arrayDateTransforme[] = $dateTransforme;
                }

                // On termine de compléter l'affichage puis on l'affiche
                $htmlBalise .= "</ul></div>";
                echo $htmlBalise;

                // On créé la deuxième partie de notre affichage final. Elle correspond à la liste des recherches par date
                $htmlContent = "<div class='containerGlobal'>
                                    <div class='wrapper'>
                                        <div class='containerTitle'>
                                            Historique des recherches de ".$user." sur les $purgeMois derniers mois (purge automatique) :
                                        </div>
                                        <a class='boutonRetour' href='./index.php'>
                                            Retour
                                        </a>
                                    </div>
                                <div class='grandContainer'>";


                // Pour chaque élément dans $data
                for($i = 0; $i < $nbObjectInArray; $i++){

                    // On ajoute à l'affichage le bloc dont l'id est égal à $dateTransforme 
                    $htmlContent .= "<div class='containerRechercheDate'><div class='dateRecherche' id='".$arrayDateTransforme[$i]."'>• Recherche(s) du : ".$arrayDateTransforme[$i]."</div>";
                    
                    // Le tableau $data ayant 3 niveaux on passe dans le deuxième qui contient les tableaux de chaque recherches à la date $i
                    foreach($data[$i] as $dataTable){    
                        
                        // Retouner $dataTable pour obtenir le résultat de la dernière recherche en haut de page
                        $dataTable = array_reverse($dataTable);
                        
                        // puis dans le troisieme qui contient les données d'une recherche à la date $i
                        foreach($dataTable as $dataTableContent){

                            // On parcourt donc chaque recherche
                            // On en extrait l'heure, le mode de recherche et le contenu
                            // Il doit toujours y avoir 3 informations sinon il faudra modifier $dataTableContent[x]
                            // On ajoute à l'affichage une section pour chaque recherche
                            $htmlContent .= "<form method='post' class='historySection'>

                                                <header class='historySectionHeader'>".$dataTableContent[0]." - ".$dataTableContent[1]."</header>
                                                <input type='text' name='typeSearch' style='display:none;' value=".getOptionValueTypeSearch($dataTableContent[1])." >
                                                ";

                                if(getOptionValueTypeSearch($dataTableContent[1]) == "search-supportGER"){
                                   
                                    $htmlContent .= "<input name='supportGerTypeSearch' value=".getOptionValueSupportGerTypeSearch($dataTableContent[1])." style='display:none;'>";
                                }

                                if(getOptionValueTypeSearch($dataTableContent[1]) == "search-GER"){
                                   
                                    $htmlContent .= "<input name='database' value=".getDatabaseValueGer($dataTableContent[1])." style='display:none;'>";
                                }

                                if(!empty($dataTableContent[2])){

                                    $htmlContent .= "<div class='historySectionContent'><textarea type='text' name='searchBar'>".$dataTableContent[2]."</textarea>";

                                    if($dataTableContent[2] != "Purge effectuée"){
                                        $htmlContent .= "<button class='historyButton' type='submit' name='submitSearch'>Relancer la recherche</button>";
                                    }
                                }
                                else{
                                    $htmlContent .= "<div class='historySectionContent'><textarea type='text' name='searchBar'>Recherche de QD</textarea>";
                                }

                                $htmlContent .= "</div>";
                                
                                if($dataTableContent[3] != "" || $dataTableContent[3] != null){
                                    $htmlContent .= "<div class='historySectionContentResult'> Résultat(s) de la recherche : ".$dataTableContent[3]."</div>";
                                }
                                                
                                $htmlContent .= "</form>"; 
                                                            
                        }                
                    }
                    // On ferme le bloc correspondant à la date $i
                    $htmlContent .= "</div>";
                }

                // On termine de compléter l'affichage puis on l'affiche
                $htmlContent .= "</div></div>";            
                echo $htmlContent;

                // Remettre le tableau dans l'ordre de départ pour qu'il n'y ai pas de reverse du tableau dans un sens puis dans l'autre à chaque rechargement
                $data = array_reverse($data);


                

                // Comme des modifiactions ont pu avoir lieu lorsqu'on a voulu supprimer de $data les recherches des dates antérieures à 1 mois par rapport à la date actuelle, on réécrit dans notre fichier le contenu de $data (qu'il ait changé ou non)
                file_put_contents($cheminFichier, json_encode($data));


                

            }// Si notre fichier ne contenait pas de données
            else{
                // On affiche un message pour signaler que l'historique est vide
                $htmlContent = "<div class='containerGlobal'><div class='wrapper'><div class='containerTitle'>Historique des recherches de ".$user." : Aucune données</div><a class='boutonRetour' href='./index.php'>Retour</a></div><div class='grandContainer'></div></div>";
                echo $htmlContent;
            }
        
        }
    }
    else{
        echo "<div class='o-alert'>Impossible d'accéder à votre historique : fichier de logs inaccessible.</div>";
    }

    
}



?>