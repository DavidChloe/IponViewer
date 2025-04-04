<?php 
    $iar = $_GET["iar"];
    $base = $_GET["base"];
?>

<!DOCTYPE html>
<html>

<head>
    <!-- En-tête de la page -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title><?php echo $iar ?> - Fiche Navette</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../public/css/styles.css" />

</head>

<body>


<?php 
    // Include
    include '../config/database_functions.php';
    include '../config/variables_globales.php';

    if($base == 'IPON'){
        $connexionIpon = DatabaseConnectionPool::createConnection('Ipon');
        $bdd = $connexionIpon[3];
        $url = "https://ipon-ihm.sso.francetelecom.fr/common/uobject.jsp?object=";
    }
    elseif($base == 'RIP'){
        $connexionIponRip = DatabaseConnectionPool::createConnection('IponRip');
        $bdd = $connexionIponRip[3];
        $url = "https://iponrip-ihm.sso.francetelecom.fr/common/uobject.jsp?object=";
    }
    
    

    if (!empty($iar)) {
        $string = "";

        $requestLignes = "select OBJECT_ID from RRE_AT where IAR_NDFICTIF = '{$iar}'"; //0590214454 KO - 0555060345 OK - 0471472467 doublon
        $prepareRequestLignes = $bdd->prepare($requestLignes);
        $prepareRequestLignes->execute();
        $resultsLignes = $prepareRequestLignes->fetchAll(PDO::FETCH_ASSOC);




        $countLignes = count($resultsLignes);


        if($countLignes == 1)
        {
            $string .= "
            <div class='o-rule__m-container o-rule__m-container--green'>
            <h4 class='o-rule__a-title'>$countLignes Fiche Navette - CONFORME</h4>
                <nav class='o-rule__a-more '>
                    <ul>
                        <u>Pour l'IAR :</u> $iar";
                        foreach ($resultsLignes as $value) {
                            $object_id = $value["OBJECT_ID"];
                            $string .= "<li><a href='$url$object_id' target='_blank'>".$value["OBJECT_ID"]."</li>";
                        }
            $string .="
                    </ul>
                </nav>
            </div>";
        }
        else 
        {
            $string .= "
            <div class='o-rule__m-container o-rule__m-container--red'>
            <h4 class='o-rule__a-title'>$countLignes Fiche(s) Navette - ERREUR</h4>
                <nav class='o-rule__a-more '>
                    <ul>
                        <u>Pour l'IAR :</u> $iar";
                        foreach ($resultsLignes as $value) {
                            $object_id = $value["OBJECT_ID"];
                            $string .= "<li><a href='$url$object_id' target='_blank'>".$value["OBJECT_ID"]."</li>";
                        }
            $string .="
                    </ul>
                </nav>
            </div>";
        }

        echo $string;
    }
    else{

    }



?>

</body>
</html>