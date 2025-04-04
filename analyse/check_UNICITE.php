<?php

// FICHIER APPELE DANS : _base.php

// FONCTION UTILISEE DANS : _base.php

// DESCRIPTION : 

/*
    Cette fonction affiche un cadre contenant des liens vers des script php permettant de scanner la fiche navette et le compte d'un client pour rechercher des anomalies
    Le cadre est de couleur grise
*/

// ARGUMENTS :
/*
    $nameDatabase : 
        type : chaine de caractère
        description : contient le nom de la base de données utilisée pour la recherche
    
    $ndVia :
        type : chaine de caractère
        description : contient le ND ou le VIA passé dans la barre de recherche par l'utilisateur 
*/

// RETOUR : chaîne de caractère

function rule_check_unicite ($nameDatabase, $ndVia) {

    // Si la recherche de l'utilisateur n'est pas vide (= l'utilisateur a lancé une recherche avec un ND ou VIA)
    if (!empty($ndVia) ) {

        // Initialiser le timer
        $time_start = microtime(true);

        // Initiliser la variable d'affichage
        $string = "";

        // Si la base de données sélectionnée est IPON RIP :
        // if($nameDatabase =='IPON RIP') {
        //     // Modifier l'appelation de la base de données sélectionnée
        //     $nameDatabase = 'IPONRIP';
        // }

        // Compléter l'affichage
        $string .= "
        <div class='o-rule__m-container o-rule__m-container--gray'>
        <h4 class='o-rule__a-title'>Vérification Unicité IAR</h4>
            <nav class='o-rule__a-more '>";
            $string .="<ul>sur Fiche Navette : <a href='analyse/test_unicite_fiche_navette.php?iar=$ndVia&base=$nameDatabase' target='_blank'>$ndVia</a></ul></nav>";
            $string .="<nav class='o-rule__a-more '><ul>sur Compte Client : <a href='analyse/test_unicite_compte.php?iar=$ndVia&base=$nameDatabase' target='_blank'>$ndVia</a></ul>";
        
        // Récupération du temps écoulé depuis le lancement du premier timer
        $time_end = microtime(true);
        $time = round(($time_end - $time_start) * 1000, 3);

        $string .="
                </nav><div class='timer'>Temps de réponse : $time ms</div></div>
            </div>"; 

        // Afficher le cadre
        echo $string;
    }
}
?>