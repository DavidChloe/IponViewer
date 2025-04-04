<?php 
// FICHIER APPELE DANS : ControllerHeader.php

// FONCTION UTILISEE DANS : searchModePTGER.php, searchModeSupportGER.php 

// DESCRIPTION : 

/*  
    Permet de vérifier que les informations trouvés dans Ipon à partir de l'id ipon de géoreso sont conforment à celles trouvées dans Géoreso pour cet id ipon
*/

// ARGUMENTS :

/*  
    $objetGER :
        type : array
        description : récupère les information de Géoreso par rapport à l'id ipon passé dans la barre de recherche
    
    $objetIPON :
        type : array
        description : récupère les information d'Ipon par rapport à l'id ipon passé dans la barre de recherche
    
    $typeSite : 
        type : chaine de caractères
        description : récupère le type de site (immeuble, chambre, appui_ft, appui_erdf, armoire)
    
*/

// RETOUR : 

/*  
    type : array
    description : renvoie un tableau contrenant la couleur du texte à afficher et le commentaire d'analyse des informations
*/


function conformiteSiteSupport($objetGER = [], $objetIPON = [], $typeSite){


    // Récupérer les informations utiles à l'analyse dans les tableaux Ipon et Géoreso
    $ref_site_GER = trim($objetGER['id_metier_site']);
    $ref_site_IPON = trim($objetIPON['SITE']);
    $ref_PT_GER = trim($objetGER['ref_pt']);
    $ref_PT_IPON = trim($objetIPON['NUM_PT']);
    $id_in_GER = trim($objetGER['objectid_ipon']);
    $id_in_IPON = trim($objetIPON['OBJECT_ID']);  

    // Conserver la valeur avant traitement
    $id_in_GER_before = $id_in_GER;

    // Initialiser le retour par défaut
    $retour = ['red','Erreur : type support inconnu. ID Ipon dans base GER : '.$id_in_GER_before.'.'];
    
    

   
    // Supprimer le R (marque du RIP) s'il est présent pour faire la comparaison dans la suite
    if(substr($id_in_GER, 0, 1) === "R"){
        $id_in_GER = substr($id_in_GER, 1);
    }

    // en fonction du type de site support
    switch($typeSite){
        
        // Dans le cas d'un immeuble
        case 'immeuble':

            // Si les id métier, les numéros de PT et les id Ipon sont pareils dans les deux tableaux alors les informations sont conformes et le retour sera de couleur verte
            if ($ref_site_GER == $ref_site_IPON && strcmp($ref_site_GER, $ref_site_IPON) == 0 
                && $ref_PT_GER == $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) == 0
                && $id_in_GER == $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) == 0) 
            {
                $retour = ['green','Informations correspondantes dans le tableau.'];
            }

            // Si les id métier ne sont pas pareils dans les deux tableaux alors les informations sont non conformes et le retour sera de couleur rouge
            if ($ref_site_GER != $ref_site_IPON && strcmp($ref_site_GER, $ref_site_IPON) != 0 
                && $ref_PT_GER == $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) == 0
                && $id_in_GER == $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) == 0) 
            {
                $retour = ['red','Attention : la référence de type support est différente dans le tableau. <br> - côté Ipon : '.$ref_site_IPON.'  <br> - côté GER : '.$ref_site_GER.'.'];
            }

            // Si les numéros de PT ne sont pas pareils dans les deux tableaux alors les informations sont non conformes et le retour sera de couleur rouge
            if ($ref_site_GER == $ref_site_IPON && strcmp($ref_site_GER, $ref_site_IPON) == 0 
                && $ref_PT_GER != $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) != 0
                && $id_in_GER == $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) == 0) 
            {
                $retour = ['red','Attention : la référence du PT est différente dans le tableau. <br> - côté Ipon : '.$ref_PT_IPON.'  <br> - côté GER : '.$ref_PT_GER.'.'];
            }

            // Si les id Ipon ne sont pas pareils dans les deux tableaux alors les informations sont non conformes et le retour sera de couleur rouge
            if ($ref_site_GER == $ref_site_IPON && strcmp($ref_site_GER, $ref_site_IPON) == 0 
                && $ref_PT_GER == $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) == 0
                && $id_in_GER != $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) != 0) 
            {
                $retour = ['red','Attention : les identifiants sont différents dans le tableau. <br> - côté Ipon : '.$id_in_IPON.'  <br> - côté GER : '.$id_in_GER.'.'];
            }

            // Si les id métier et les numéros de PT ne sont pas pareils dans les deux tableaux alors les informations sont non conformes et le retour sera de couleur rouge
            if ($ref_site_GER != $ref_site_IPON && strcmp($ref_site_GER, $ref_site_IPON) != 0 
                && $ref_PT_GER != $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) != 0
                && $id_in_GER == $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) == 0) 
            {
                $retour = ['red','Attention : les références de type support et les références du PT sont différentes dans le tableau. <br> - côté Ipon : '.$ref_site_IPON.' '.$ref_PT_IPON.'  <br> - côté GER : '.$ref_site_GER.' '.$ref_PT_GER.'.'];
            }

            // Si les id métier et les id Ipon ne sont pas pareils dans les deux tableaux alors les informations sont non conformes et le retour sera de couleur rouge
            if ($ref_site_GER != $ref_site_IPON && strcmp($ref_site_GER, $ref_site_IPON) != 0 
                && $ref_PT_GER == $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) == 0
                && $id_in_GER != $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) != 0) 
            {
                $retour = ['red','Attention : les références de type support et les identifiants sont différents dans le tableau. <br> - côté Ipon : '.$ref_site_IPON.' '.$id_in_IPON.'  <br> - côté GER : '.$ref_site_GER.' '.$id_in_GER.'.'];
            }

            // Si les numéros de PT et les id Ipon ne sont pas pareils dans les deux tableaux alors les informations sont non conformes et le retour sera de couleur rouge
            if ($ref_site_GER == $ref_site_IPON && strcmp($ref_site_GER, $ref_site_IPON) == 0 
                && $ref_PT_GER != $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) != 0
                && $id_in_GER != $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) != 0) 
            {
                $retour = ['red','Attention : les références du PT et les identifiants sont différents dans le tableau. <br> - côté Ipon : '.$ref_PT_IPON.' '.$id_in_IPON.'  <br> - côté GER : '.$ref_PT_GER.' '.$id_in_GER.'.'];
            }
            // Si rien n'est pareil alors les informations sont non conformes et le retour sera de couleur rouge
            if ($ref_site_GER != $ref_site_IPON && strcmp($ref_site_GER, $ref_site_IPON) != 0 
                && $ref_PT_GER != $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) != 0
                && $id_in_GER != $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) != 0) 
            {
                $retour = ['red','Attention : les références de type support, les références du PT et les identifiants sont différents dans le tableau. <br> - côté Ipon : '.$ref_site_IPON.' '.$ref_PT_IPON.' '.$id_in_IPON.'  <br> - côté GER : '.$ref_site_GER.' '.$ref_PT_GER.' '.$id_in_GER_before.'. <br><br> Il est possible que votre ID existe en double (avec et sans le R) dans une de vos bases.'];
            }

        break;


        // Même chose pour les autre type de site support sauf qu'on fait des comparaison en plus : 
        /*
            - il faut que la 1ere partie de l'id métier côté Ipon (les 2 lettres) correspondent aux 2 premières lettres du type de site support dans Géoreso
            - il faut que la 2eme partie de l'id métier côté Ipon corresponde aux chiffres de la première partie de l'id métier site dans géoreso
        
        */
        case 'chambre':
        case 'appui_ft':
        case 'appui_erdf':
        case 'armoire':

            $explode_ref_site_GER = explode('/', $ref_site_GER);
            $first_part_ref_site_GER = $explode_ref_site_GER[0];

            $explode_ref_site_IPON = explode('/', $ref_site_IPON);
            $second_part_ref_site_IPON = $explode_ref_site_IPON[1];


            if($id_in_GER == $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) == 0
            && strtoupper(substr($typeSite, 0, 2)) === strtoupper(substr($ref_site_IPON, 0, 2))
            && $first_part_ref_site_GER === $second_part_ref_site_IPON
            && $ref_PT_GER == $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) == 0)
            {
                $retour = ['green','Informations correspondantes dans le tableau.'];            
            }

            if($id_in_GER != $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) != 0
            && strtoupper(substr($typeSite, 0, 2)) === strtoupper(substr($ref_site_IPON, 0, 2))
            && $first_part_ref_site_GER === $second_part_ref_site_IPON
            && $ref_PT_GER == $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) == 0)
            {
                $retour = ['red','Attention : les identifiants sont différents dans le tableau. <br> - côté Ipon : '.$id_in_IPON.'  <br> - côté GER : '.$id_in_GER.'.'];            
            }

            if($id_in_GER == $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) == 0
            && strtoupper(substr($typeSite, 0, 2)) !== strtoupper(substr($ref_site_IPON, 0, 2))
            && $first_part_ref_site_GER === $second_part_ref_site_IPON
            && $ref_PT_GER == $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) == 0)
            {
                $retour = ['red','Attention : les types de site sont différents dans le tableau.'];            
            }

            if($id_in_GER == $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) == 0
            && strtoupper(substr($typeSite, 0, 2)) === strtoupper(substr($ref_site_IPON, 0, 2))
            && $first_part_ref_site_GER !== $second_part_ref_site_IPON
            && $ref_PT_GER == $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) == 0)
            {
                $retour = ['red','Attention : les références de type support sont différentes dans le tableau. <br> - côté Ipon : '.$ref_site_IPON.' <br> - côté GER : '.$ref_site_GER.'.'];            
            }

            if($id_in_GER == $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) == 0
            && strtoupper(substr($typeSite, 0, 2)) === strtoupper(substr($ref_site_IPON, 0, 2))
            && $first_part_ref_site_GER === $second_part_ref_site_IPON
            && $ref_PT_GER != $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) != 0)
            {
                $retour = ['red','Attention : les références du PT sont différentes dans le tableau. <br> - côté Ipon : '.$ref_PT_IPON.' <br> - côté GER : '.$ref_PT_GER.'. le strcmp : '.strcmp($ref_PT_GER, $ref_PT_IPON).''];            
            }

            if($id_in_GER != $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) != 0
            && strtoupper(substr($typeSite, 0, 2)) !== strtoupper(substr($ref_site_IPON, 0, 2))
            && $first_part_ref_site_GER === $second_part_ref_site_IPON
            && $ref_PT_GER == $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) == 0)
            {
                $retour = ['red','Attention : les identifiants et les types de site sont différents dans le tableau. <br> - côté Ipon : '.$id_in_IPON.' '.$ref_site_IPON.' <br> - côté GER : '.$id_in_GER.' '.$ref_site_GER.'.'];            
            }

            if($id_in_GER != $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) != 0
            && strtoupper(substr($typeSite, 0, 2)) !== strtoupper(substr($ref_site_IPON, 0, 2))
            && $first_part_ref_site_GER == $second_part_ref_site_IPON
            && $ref_PT_GER == $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) == 0)
            {
                $retour = ['red','Attention : les identifiants et les types de site sont différents dans le tableau. <br> - côté Ipon : '.$id_in_IPON.' '.$ref_site_IPON.' <br> - côté GER : '.$id_in_GER.' '.$ref_site_GER.'.'];            
            }

            if($id_in_GER != $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) != 0
            && strtoupper(substr($typeSite, 0, 2)) === strtoupper(substr($ref_site_IPON, 0, 2))
            && $first_part_ref_site_GER !== $second_part_ref_site_IPON
            && $ref_PT_GER == $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) == 0)
            {
                $retour = ['red','Attention : les identifiants et les numéros des types de site sont différents dans le tableau. <br> - côté Ipon : '.$id_in_IPON.' '.$ref_site_IPON.' <br> - côté GER : '.$id_in_GER.' '.$ref_site_GER.'.'];            
            }

            if($id_in_GER != $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) != 0
            && strtoupper(substr($typeSite, 0, 2)) === strtoupper(substr($ref_site_IPON, 0, 2))
            && $first_part_ref_site_GER === $second_part_ref_site_IPON
            && $ref_PT_GER != $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) != 0)
            {
                $retour = ['red','Attention : les identifiants et les références du PT sont différents dans le tableau. <br> - côté Ipon : '.$id_in_IPON.' '.$ref_PT_IPON.' <br> - côté GER : '.$id_in_GER.' '.$ref_PT_GER.'.'];            
            }

            if($id_in_GER == $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) == 0
            && strtoupper(substr($typeSite, 0, 2)) !== strtoupper(substr($ref_site_IPON, 0, 2))
            && $first_part_ref_site_GER !== $second_part_ref_site_IPON
            && $ref_PT_GER == $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) == 0)
            {
                $retour = ['red','Attention : les types de site et les numéros des types de site sont différents dans le tableau. <br> - côté Ipon : '.$ref_site_IPON.' <br> - côté GER : '.$ref_site_GER.'.'];            
            }

            if($id_in_GER == $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) == 0
            && strtoupper(substr($typeSite, 0, 2)) !== strtoupper(substr($ref_site_IPON, 0, 2))
            && $first_part_ref_site_GER == $second_part_ref_site_IPON
            && $ref_PT_GER != $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) != 0)
            {
                $retour = ['red','Attention : les types de sites et les références du PT sont différents dans le tableau. <br> - côté Ipon : '.$ref_site_IPON.' '.$ref_PT_IPON.' <br> - côté GER : '.$ref_site_GER.' '.$ref_PT_GER.'.'];            
            }

            if($id_in_GER == $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) == 0
            && strtoupper(substr($typeSite, 0, 2)) === strtoupper(substr($ref_site_IPON, 0, 2))
            && $first_part_ref_site_GER !== $second_part_ref_site_IPON
            && $ref_PT_GER != $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) != 0)
            {
                $retour = ['red','Attention : les numéros des types de site et les références du PT sont différents dans le tableau. <br> - côté Ipon : '.$ref_site_IPON.' '.$ref_PT_IPON.' <br> - côté GER : '.$ref_site_GER.' '.$ref_PT_GER.'.'];            
            }




            if($id_in_GER != $id_in_IPON && strcmp($id_in_GER, $id_in_IPON) != 0
            && strtoupper(substr($typeSite, 0, 2)) !== strtoupper(substr($ref_site_IPON, 0, 2))
            && $first_part_ref_site_GER !== $second_part_ref_site_IPON
            && $ref_PT_GER != $ref_PT_IPON && strcmp($ref_PT_GER, $ref_PT_IPON) != 0)
            {
                $retour = ['red','Attention : les références de type support, les références du PT et les identifiants sont différents dans le tableau. <br> - côté Ipon : '.$ref_site_IPON.' '.$ref_PT_IPON.' '.$id_in_IPON.'  <br> - côté GER : '.$ref_site_GER.' '.$ref_PT_GER.' '.$id_in_GER.'.'];            
            }

            
        break;

        default : 

            $retour = ['red','Erreur : type support inconnu côté Ipon : '.$typeSite.'.'];

        break;
    }


    
    return $retour;
}





?>