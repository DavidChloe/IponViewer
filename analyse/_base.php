<?php

// FICHIER APPELE DANS : ControllerHeader.php

// FONCTION UTILISEE DANS : searchModeNDVIA.php

// IMPORT :

// Import des fichiers des fonctions d'analyse utilisées par rule_automatic(...)
@require "./analyse/connexion_PM.php";
@require "./analyse/check_AT.php";
@require "./analyse/check_UNICITE.php";
@require "./analyse/get_links_client.php";
@require "./analyse/check_ERROR.php";
@require "./analyse/check_PTO_ONT.php";
@require "./analyse/check_route_PTF.php";
// @require "./analyse/check_AT_FICHE_NAVETTE.php";





// DESCRIPTION : 

/*  
    Function to apply all rules in one-shot

    Cette fonction a pour but principal de centraliser les fonction pour l'affichages des cadres informatifs de la recherche par ND/VIA
    Son deuxieme objectif et de centraliser les retour de ces fonctions afin de construire le résumé de la recherche qui sera passé dans l'historique de recherche
    Elle fournie toutes les données utiles aux fonctions qu'elle contient et est appelée dans le cadre d'une recherche par ND/VIA
*/


// ARGUMENTS :
/* 
    $id :
        type : array
        description : fournit le/les object_id 

    $data :
        type : array
        description : les données résultantes de l'execution de toutes les requêtes de la fonction executeRequests(...)
    
    $bdd : 
        type : mixed
        description : instance de la classe PDO permettant la connexion et la communication avec une base de données
    
    $nameDatabase :
        type : chaine de caractère
        description : contient le nom de la base de données utilisée pour la recherche
    
    $ndVia :
        type : chaine de caractère
        description : contient le ND ou le VIA passé dans la barre de recherche par l'utilisateur
    
    $checkCompteDossier :
        type : entier numérique
        description : contient un entier dont l'interprétation de la valeur permet de connaitre la présence d'un compte et d'un dossier client

    $refAT :
        type : chaine de caractère
        description : contient le nom de l'AT dans le dossier et/ou le compte client
*/

// RETOUR : 
    
/*
    type : Void
*/



function rule_automatic(array $id, array $data, $bdd, $nameDatabase, $ndVia, $checkCompteDossier, $refAT) {


    // Initialiser le résumé
    $resume = "<br><br>";
    
    // Cadre contenant les liens du compte/dossier client
    // Renvoie un tableau
    
    $resultLinks = rule_get_links_client($data, $nameDatabase);

    
    // Si le tableau retourné n'est pas vide :
    if(count($resultLinks) != 0){

        // Compléter le résumé
        $resume .= "Information(s) client : ";

        // Pour chaque résultat
        for($i = 0; $i<count($resultLinks); $i++){

            // Compléter le résumé avec le résultat
            $resume .= $resultLinks[$i];
            
            // Rajouter ' et ' à l'affichage si on a plusieurs résultats
            if(count($resultLinks) == 2 && $i == 0){
                $resume .= ' et ';
            }
        }
    }
    // Si le tableau retourné est vide
    else{
        // Compléter le résumé
        $resume .= "Information(s) client : pas d'information trouvée <br>";
    }
    // Compléter le résumé
    $resume .= "<br>";
    





    // PMO et PMC : Vérifier la conformité du point de connexion
    // Renvoie un tableau
    $resultMCIFO = rule_PMO_PC($id, $data);

    // La fonction peut retourner des résultats en double, on ne veut donc garder que des exemplaire unique. On élimine donc les doublons
    $resultMCIFO = array_unique($resultMCIFO);

    // Compléter le résumé 
    for($i = 0; $i<count($resultMCIFO); $i++){
        $resume .= $resultMCIFO[$i];
        $resume .= '<br>';
    }
    




    // Check no, unique et multiple AT
    // Renvoie true (= pas d'erreur) ou false (= erreur détectée)

    $resultAT = rule_check_AT($id, $data, $bdd);
    
    // Compléter le résumé en fonction du retour de la fonction
    if($resultAT){
        $resume .= 'Vérification des AT : erreur détectée <br>';
    }
    else{
        $resume .= 'Vérification des AT : pas d\'erreur détectée <br>';
    }
    


    // Affiche les liens permettant de scanner la fiche navette et le compte client
    // Ne renvoie rien

    rule_check_unicite($nameDatabase, $ndVia);





    // Vérifie la présence d'une erreur dans le compte client
    // Renvoie une chaine de caractères
    $resultError = rule_check_error($data, $nameDatabase);
    // Compléter le résumé en fonction du retour de la fonction
    if($resultError == ""){
        $resultError = "Code erreur : pas de résultat";
    }
    $resume .= $resultError.'<br>';



    // Vérifie la présence des prise PTO et ONT dans le dossier de client
    // Renvoie une chaine de caractères
    $resultPTO_ONT = rule_check_PTO_ONT($data, $nameDatabase);
    $resume .= $resultPTO_ONT.'<br>';


    // Vérifie la complétude de la route PTF
    // Renvoie un tableau de chaines de caractères 
    $resultRoutePTF = rule_check_route_PTF($id, $bdd, $checkCompteDossier, $ndVia);
    foreach ($resultRoutePTF as $retour) {
        $resume .= $retour.'<br>';
    }

    ?>

    
    <script>

        // JQuery permettant d'éxécuter une requête Ajax

        $(document).ready(function() {

            // Récupérer les paramètres nécessaires au lancement de la requête Ajax
            var param1 = '<?php echo $ndVia; ?>';
            var param2 = '<?php echo $refAT; ?>';
            var param3 = '<?php echo $nameDatabase; ?>';
            
            // Au clic sur le bouton permettant de lancer l'éxécution de la règle n°1
            $('#loadCheck_At_Fiche_Navette').click(function() {

                // Changer le logo et la classe su bouton
                // Il devient un spinner de chargement
                $('#iconMore').attr('src', 'public\\images\\loading.png');
                $('#iconMore').removeClass('moreContent').addClass('loadContent');

                
                // La requête Ajax
                $.ajax({
                    // Url du fichier php à éxécuter
                    url: './analyse/loadFunctionCheck_AT_Fiche_Navette.php',
                    // Méthode d'éxécution 
                    type: 'POST',
                    // Paramètres 
                    data: {
                        param1:param1,
                        param2:param2,
                        param3:param3
                    },
                    // Actions en cas de succès
                    success: function(response) {

                        // Remplacement du module invisible par le résultat du script php
                        $('#check_AT_Fiche_Navette').replaceWith(response);  

                        // Effacer le bloc de controle de la règle supplémentaire n°1
                        $('#regle1').css("display", "none"); 

                        // Reinitialisation du bloc de contrôle de la règle supplémentaire n°1 avec les paramètres initiaux
                        $('#iconMore').attr('src', 'public\\images\\plus.png');
                        $('#iconMore').removeClass('loadContent').addClass('moreContent');
                    },
                    // Action en cas d'échec
                    error: function(xhr, status, error) {
                        console.log(error); // Afficher l'erreur dans la console en cas d'échec
                    }
                });
            });
        });
    </script>

    
    
    <?php

    // Affichage d'un module invisible d'accueil de résultat pour la règle supplémentaire n°1
    echo "<div class='o-rule__m-container o-rule__m-container--none' id='check_AT_Fiche_Navette'></div>";

    
    // Affichage du bloc de controle permettant de lancer la règle supplémentaire n°1
    echo "    
    <div class='o-rule__m-container o-rule__m-container--gray' id='regle1'>
        
        <h4 class='o-rule__a-title' >
            Règle supplémentaire n°1 :
        </h4>
        <nav class='o-rule__a-more'>
            <div class='loadFunctionsContainer'>
                <button type='submit' class='btnLoadFunction' id='loadCheck_At_Fiche_Navette'>
                    <img src='public\images\plus.png' class='moreContent' id='iconMore' alt=''>
                </button>
                <p>Conformité AT dans fiche navette client</p>
            </div>
        </nav>
        <nav class='o-rule__a-more'>
            Durée estimée : entre 15s et 1min
        </nav>
    </div>
    ";


    saveSearchResultInHistory($resume);

}

?>



