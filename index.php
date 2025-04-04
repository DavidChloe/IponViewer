<!DOCTYPE html>
<html>

<head>
    <!-- En-tête de la page -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>
        <?php 
            // Titre apparaissant dans la navbar d'Ipon Viewer
            // Numéro de version repris à plusieurs endroits dans le code            
            $software = "IPON Viewer ";
            // /!\ conserver le format 'VX.X.X' (X pouvant être n'importe quel nombre) 
            $version = "V6.1.2";

            echo $software.$version; 
        ?>
    </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="public\js\functions.js"></script>

    <link rel="stylesheet" href="./public/css/styles.css" />
</head>

<body>
    <?php 
        include "./config/variables_globales.php";
        include "./view/base/header.php";
    ?>
</body>

</html>