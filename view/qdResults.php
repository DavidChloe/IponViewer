<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analyse QD</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" href="../public/css/styles.css" />
</head>
<body>

<?php 
include "../script/getSaveData.php";
?>

<!-- Section du loader -->
<div id="scene">
        <div class="cube-wrapper">
          <div class="cube">
          <div class="cube-faces">
              <div class="cube-face shadow"></div>
              <div class="cube-face bottom"></div>
              <div class="cube-face top"></div>
              <div class="cube-face left"></div>
              <div class="cube-face right"><span class="logo_titre">Orange</span></div>
              <div class="cube-face back"></div>
              <div class="cube-face front"><div class="rect"></div></div>
            </div>
          </div>
        </div>
        <p class="p_text">Chargement...</p>
    </div>



<section id="top_page"></section>

<form action="../index.php" method="post">

    <header class="o-header">

        <!--<h1 class="o-header__a-title"></h1>-->
    
        <!--DATABASE CHOICE-->
        <nav class="o-header__m-switchDiv o-header__m-switchDivDB">
            <div class="wrapper">
                
                <div class="wrapper_insider">
                    <a class="o-header__a-app" href="../index.php">
                        <?php echo "IPON Viewer <br>".getDataFromSaveFile("version_actuelle") ?>
                    </a>
                    
                    <img class="o-header__a-logo" src="../public/images/logo_orange.png" alt="Logo Orange" />
                </div>
            
                <div class="container">
                       
                    <div id="header_nav_toggleLightMode" style="display: none;">
                        <p class="header_nav_p">Mode</p> 
                        <!-- <img class="header_nav_img" src="../public/images/soleil-clair.png" alt="clair" > -->
                        
                    </div> 
                    
                    
                </div>
                
                <!-- <a class='boutonRetour' href='../index.php'>
                    Retour
                </a>   -->

                <button type="submit" name="submitQD" class="boutonRetour">Retour</button>

            </div>            
        </nav>
    </header>

    
</form>

<section id="section_navigate" style="display:none;">
    <a id="navigate_top" href='#top_page'>⇑</a>
    <a id='navigate_bottom' href='#bottom_page'>⇓</a>
</section>

<!-- Section pour l'affichage du temps global pris pour l'xécution d'une rehcerche -->
<br>
<section class="temps_global" id="temps_global"></section>
<br>

<section class="p-search__o-result">
    <div class="o-rule"></div>
    <?php 
        include '../script/areArraysEmptyRecursive.php';
        include '../script/saveKeyData.php';
        // include "./analyse/qd.php";
        include '../view/qdManager.php';
        // include '../config/database_functions.php';
        include '../config/variables_globales.php';
        include '../model/base.php';
    ?>
</section>


<script src="../public/js/switchTheme.js"></script>
<script src="../public/js/scrollDetection.js"></script>

<?php // Pour les voyants de connexion aux bases de données, le fichier est en include_once car on l'utilise aussi dans le ControllerHeader.php
    
    //include "../config/database_functions.php";
    include_once "../config/test_connection_database.php";  
    
    
          
?>



<?php



echo "<div class='wrapper'>
        <div class='containerTitle'>
            Résultats QD : 
        </div>
    </div>"
;

include '../controller/qdController.php';

?>
            <script src="../public/js/loader.js"></script>
            <script src="../public/js/scrollDetection.js"></script> 
            <script src="../public/js/search.js"></script> 
        <?php

?>
<section id="bottom_page"></section>
</body>
</html>