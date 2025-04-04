<?php

// To read array var-dump easily
// Source : https://www.php.net/manual/fr/function.print-r

/*

    Ce fichier regroupe différentes fonctions pour le débeugage et la consultation d'informations
    Ces fonctions ne sont pas utile pour l'utilisateur, seulement pour les développeurs. 
    C'est ici que ces fonctions sont déclarées. 

*/

// Fonction dérivée du print_r() permettant de visualiser les informations d'un tableau. Cette fonction permet d'ordonner les tableaux pour que les informations qu'ils contiennent soit plus lisible.
// Prend en paramètre un tableau
function print_r2($val){
    echo '<pre>';
    print_r($val);
    echo  '</pre>';
}

// Fonction dérivée du echo permettant de mettre à la ligne l'information qu'on souhaite lire. En temps normal, si plusieurs echo sont utilisés à la suite, le rendu se fera sur une seule ligne sans retour à la ligne, ce qui n'est pas lisible sur un grand nombre d'information (issues d'une boucle par exemple).
// Prend en paramètre une chaine de caractère
function echo_t($var){
    echo '<br>'.$var;
}

// Fonction permettant d'afficher une données dans la console du navigateur. Remplace un echo car le résultats n'est pas affiché sur la page
// Prend en paramètre (de préférence) une chaine de caractères
function consoleLog($text){
    $tab = ["data"=>$text];
    $json = json_encode($tab);
    ?>
        <script>            
            if(Object.keys(<?php echo $json; ?>).length != 0){
            
                for (const key in <?php echo $json; ?>) {
                
                    console.log(<?php echo $json; ?>[key]);
                
                }            
            }            
        </script>
    <?php
}

?>


