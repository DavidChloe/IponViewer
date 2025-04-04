<?php

// Page de d'accueil de la gestion de la QD

if(isset($_POST['submitQD'])){

    // Tableau pour la création des bouton de lancement d'analyse
    /*
        NAME : correspond au titre du bloc
        DESCRITPTION : la description de ce que fait le bloc
        POST_TAG : la valeur du champs 'name' permettant d'identifier la QD lancée
        DUREE : Information sur le temps approximatif d'attente avant affichage de résultats 
    */ 
    $qd_rules= array(
        [
            "NAME" => "QD1 : Scan QD IPON - Table RRE_AT",
            "DESCRIPTION" => "Récupère le AT ayant un nombre d'occurences > 1 dans la table RRE_AT.",
            "POST_TAG" => "post_tag_scanQD",
            "DUREE" => "Recherche Hors RIP ≈ 25 s. pour 1000 résultats <br> Recherche RIP ≈ 3 s. pour 1000 résultats",
            "AUTORISE" => '',
            "COULEUR" => ''
        ],
        [
            "NAME" => "QD2 : Scan noms immeubles IPON - Table NC_OBJECTS",
            "DESCRIPTION" => "Récupère les immeubles dont le nom n'est pas conforme à la norme imposée dans la table NC_OBJECTS (comprend les noms commençant par des espaces blancs).",
            "POST_TAG" => "post_tag_scan_nom_immeuble",
            "DUREE" => "Recherche Hors RIP ≈ 25 s. pour 100 résultats <br> Recherche RIP ≈ 10 s. pour 100 résultats",
            "AUTORISE" => '',
            "COULEUR" => ''
        ],
        [
            "NAME" => "QD3 : Scan Route PTF IPON parmis les ND/VIA",
            "DESCRIPTION" => "Récupère les routes PTF non conformes pour les clients ayant un dossier.",
            "POST_TAG" => "post_tag_scan_route_ptf",
            "DUREE" => "Durée = 50 s pour 500 enregistrements sur RIP",
            "AUTORISE" => '',
            "COULEUR" => ''
        ],
        [
            "NAME" => "QD4 : Scan Codes d'erreurs dans les dossiers",
            "DESCRIPTION" => "Récupère les ND/VIA dont les dossiers associées ont un code d'erreur donc si le code d'erreur est différent de 000 ou 509 ou 100 ou 055.",
            "POST_TAG" => "post_tag_scan_code_error",
            "DUREE" => "Idée de QD, requête bonus si reste du temps ou à faire plus tard",
            "AUTORISE" => 'disabled',
            "COULEUR" => 'brown'
        ],
        [
            "NAME" => "ABANDON : Scan dossiers à supprimer IPON",
            "DESCRIPTION" => "Récupère les identifiants des dossiers clients n'ayant pas été supprimés lorsque le compte est opérationnel.",
            "POST_TAG" => "post_tag_scan_dossiers",
            "DUREE" => "Abandon de cette QD validée le 21 juin 2023 par Christophe DIJOUX",
            "AUTORISE" => 'disabled',
            "COULEUR" => 'brown'
        ]
    );

    // Création du formulaire
    $htmlContent = "
        <form class='containerGlobal' target='_blank' action='./view/qdResults.php' method='post'>
            <div class='wrapper'>
                <div class='containerTitle'>
                    Gestion QD : 
                </div>
                <a class='boutonRetour' href='./index.php'>
                    Retour
                </a>
            </div>
            <ul>
            <li>
            Rechercher sur : 
            <select name='database'>
                <option value='IPON' >Hors RIP</option>
                <option value='RIP' checked>RIP</option>
            </select>
            </li>
            <li>
            Nombre de résultats maximum :
            <input name='num_fetch_row' type='number' min='0' max='30000' value='1000' style='width: 60px;'>
            </li>
            <li>
            Type d'identifiant :
                <select name='nd_or_via'>
                <option value='VIA' checked>VIA</option>
                <option value='ND' >ND</option>
            </select>
            (uniquement pour QD3)
            </li>
            </ul>

            <div class='grandGridContainer'>
        
    ";
            
    // Affichage 
    for($i = 0; $i < count($qd_rules); $i++){

        $htmlContent .= "<button class='qd_rule_container' type='submit' name='".$qd_rules[$i]['POST_TAG']."' style='background-color: ".$qd_rules[$i]['COULEUR']."' ".$qd_rules[$i]['AUTORISE'].">
                <h3 class='titre_qd'>".$qd_rules[$i]['NAME']."</h3>
                <input type='hidden' name='title[".$i."]' value='".$qd_rules[$i]['NAME']."'>
                <br>
                <nav class='content_qd'>
                    ".$qd_rules[$i]['DESCRIPTION']."
                    <input type='hidden' name='description' value='".$qd_rules[$i]['DESCRIPTION']."'>
                    <br><br>
                    ".$qd_rules[$i]['DUREE']."
                    <input type='hidden' name='duree' value='".$qd_rules[$i]['DUREE']."'>
                    <br>                   
                </nav>
            </button>"
        ;
    }

    $htmlContent .= "</div></div></form>";
    
    echo $htmlContent;


}




?>