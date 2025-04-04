<!-- 
    Cette page contient le formulaire principale autour duquel s'articule toute l'architecture de l'application
-->



<!--
    Section réservée au loader de page.
    Il n'apparait que lors des temps de chargement.
    Il est géré par le fichier loader.js
-->
<!-- <section id="page_loader"> -->
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

<!-- </section> -->

<!--
    Section invisible positionnée en haut de page.
    Utilisé comme lien d'ancrage pour la navigation afin remonter rapidement en haut de la page.
    Lié plus bas dans le code (voir section où id=section_navigate)
-->
<section id="top_page"></section>

<!--
    Partie majeure de la page correspondant au formulaire d'envoi.
    Renvoie vers lui même (-> action=""). On ne charge donc pas une nouvelle page à chaque envoie, on la recharge avec du nouveau contenu.
-->
<form class="o-header__m-formSearch" action="" method="post">

    <!-- 
        Lien vers la page de l'installer d'Ipon Viewer : iponViewerInstaller.php
        Apparait en haut à gauche de la fenêtre de l'application uniquement lorsqu'une nouvelle version disponible est détectée. Le reste du temps, cet élément n'apparait pas.
        La gestion de son contenu et de son affichage se fait dans compareSoftwareVersion.php 
    -->
    <a href='script/iponViewerInstaller.php'><div id="updateAvailable"></div></a>

    <header class="o-header">

        <nav class="o-header__m-switchDiv o-header__m-switchDivDB">
            <div class="wrapper">

                <!-- 
                    Nom et version affichés dans le bandeau. $software et $version sont récupérés depuis l'index.php (ce lien peut se faire car header.php est appelé dans l'index.php) 
                -->
                <div class="wrapper_insider">
                    <a class="o-header__a-app" href="./index.php">
                        <?php echo $software."<br>".$version ?>
                    </a>
                    <!-- Logo d'orange -->
                    <img class="o-header__a-logo" src="./public/images/logo_orange.png" alt="Logo Orange" />
                </div>



                <?php
                    // Modulation d'affichage : les boutons de sélection pour les bases de données n'apparaissent que si on n'est pas dans l'onglet d'historique
                    if(!isset($_POST['historySearch']) && !isset($_POST['submitQD'])){
                ?>
                    <!--
                        Checkbox pour la sélection de la base de données
                    -->
                    <div class="container" id="databaseButtonChoice">
                        <div class="wrapper_insider">
                            <input type="radio" value="IPON" name="database" id="IPON" checked>
                            <label for="IPON" class="wapper_insider_label">IPON / Géoreso</label>
                        </div>
                        <div class="wrapper_insider">
                            <input type="radio" value="RIP" name="database" id="RIP">
                            <label for="RIP" class="wapper_insider_label">IPON RIP / Géoreso RIP</label>
                        </div>
                    </div>
                <?php
                    }
                ?>

                <div class="container">

                    <!-- 
                        Bouton de changement de thème (sombre/clair)
                    -->
                    <div id="header_nav_toggleLightMode">
                        <p class="header_nav_p">Mode</p>
                        <img class="header_nav_img" src="public\images\soleil-clair.png" alt="soleil">
                    </div>


                    <?php
                        // Modulation d'affichage : le bouton d'historique n'apparait pas lorsqu'on se troive déjà dans l'historique
                        if(!isset($_POST['historySearch'])){
                    ?>
                        <!-- 
                            Bouton pour consulter l'historique
                        -->
                        <button id="header_nav_historique" type="submit" name="historySearch">
                            <img class="header_nav_img" src="public\images\historique.png" alt="Historique des recherches" title="Consulter l'historique des recherches de l'utilisateur.">
                        </button>

                    <?php
                        }
                    ?>

                    <!-- ouvrir dossier de documentation local avec php -> href='./controller/OpenDocumentationFolder.php' -->
                    <a id="header_nav_documentation" href="https://orange0.sharepoint.com/:f:/r/sites/AmliorationContinue_Domaine_Reseau/Documents%20partages/Ecosyst%C3%A8me_Conception/IPON%20Viewer/Documentation?csf=1&web=1&e=toFrXC" target="_blank">
                        <img class="header_nav_img" src="public\images\documentation.png" alt="Documentation Ipon Viewer" title="Accéder à la documentation de l'application sous Teams : &#10;Echange_DomaineRéseau > Ecosystème_Conception > Fichiers > IPON Viewer > Documentation.">
                    </a>
                </div>
                <!--
                    Bouton du menu gestion QD
                -->
                <div class="container" title="Accès à la fenêtre de lancement des requêtes de QD.">
                    <a href="./analyse/qd.php"><button class="o-header__a-search-qd" type="submit" name="submitQD">Gestion QD</button></a>
                </div>

                <?php
                    // Affichage lien vers OBRAC

                    // Récupération du dossier parent à celui d'Ipon Viewer
                    $repertoireParent = realpath(__DIR__ . '/../../..');

                    // S'il s'agit bien d'un dossier
                    if(is_dir($repertoireParent)){

                        // Initialiser un flag à false
                        $flag = false;
                        // Récupérer le contenu du dossier
                        $rep = scandir($repertoireParent);

                        // Dans une boucle : 
                        foreach ($rep as $key => $value) {
                            // Vérifier si le dossier parent contient un dossier Outils
                            if($value == 'Outils'){
                                // Si oui, passer le flag à true
                                $flag = true;
                                // Compléter le chemin vers le dossier Outils
                                $repertoireParent = $repertoireParent.'/Outils';
                                // Quitter la boucle
                                break;
                            }
                        }

                        // Si on a bien trouvé un répertoire Outils et que le dossier n'est pas vide
                        // Afficher le lien vers OBRAC
                        if($flag == true && count(scandir($repertoireParent)) > 2){
                ?>

                    <!-- 
                        lien vers OBRAC
                    -->
                    <div class="container">
                        <button class="btn_obrac">
                            <a href="https://localhost/html/Outils/Artemis/index.php" title='Lien à http://localhost:8082/html/Outils/Artemis/index.php' target="_blank" id="lien_obrac" style='color:white'>Accès OBRAC</a>
                        </button>
                    </div>

                <?php
                        } 
                        else{
                            ?>

                                <!-- 
                                    lien vers OBRAC
                                -->
                                <div class="container">
                                    <button class="btn_obrac off" title='Lien à http://localhost:8082/html/Outils/Artemis/index.php inaccessible' disabled='true'>
                                        Accès OBRAC
                                    </button>
                                </div>

                            <?php
                        }                               
                    }
                ?>

            </div>
        </nav>
    </header>

    <?php
        // Modulation d'affichage : les voyant de connexion aux bases de données n'apparaissent pas si on se trouve dans l'historique
        if(!isset($_POST['historySearch'])){
    ?>

        <!-- Voyants d'information sur l'état de la connexion aux bases de données -->
        <section id="section_check_bdd">
            <!-- <p id="bdd_last_connection"> Dernier test de connexion bdd : </p>
            <br> -->
            <div class="container">
                <div class="container_verticale">
                    <!-- Voyant de connexion à Ipon -->
                    <div class="wrapper_insider">
                        <p class="bdd_light_text">Connexion IPON : </p>
                        <div class="bdd_light" id="ipon_light"></div>
                    </div>
                    <div class="subwrapper_insider">
                        <div id="heure_ipon"></div>
                        <p> - </p>
                        <div id="delais_ipon"></div>
                        <div class='refresh_button' id="iponRefresh">
                            <img class='refresh_image' src="public\images\refresh.svg" alt="" title="Recharge la connexion à Ipon">
                        </div>
                    </div>
                </div>

                <div class="container_verticale">
                    <!-- Voyant de connexion à Ipon Rip -->
                    <div class="wrapper_insider">
                        <p class="bdd_light_text">Connexion IPON RIP : </p>
                        <div class="bdd_light" id="ipon_rip_light"></div>
                    </div>
                    <div class="subwrapper_insider">
                        <div id="heure_ipon_rip"></div>
                        <p> - </p>
                        <div id="delais_ipon_rip"></div>
                        <div class='refresh_button' id="iponRipRefresh">
                            <img class='refresh_image' src="public\images\refresh.svg" alt="" title="Recharge la connexion à Ipon Rip">
                        </div>
                    </div>
                </div>

                <div class="container_verticale">
                    <!-- Voyant de connexion à Géoreso -->
                    <div class="wrapper_insider">
                        <p class="bdd_light_text">Connexion Géoreso : </p>
                        <div class="bdd_light" id="georeso_light"></div>
                    </div>
                    <div class="subwrapper_insider">
                        <div id="heure_georeso"></div>
                        <p> - </p>
                        <div id="delais_georeso"></div>
                        <div class='refresh_button' id="georesoRefresh">
                            <img class='refresh_image' src="public\images\refresh.svg" alt="" title="Recharge la connexion à Géoreso">
                        </div>
                    </div>
                </div>

                <div class="container_verticale">
                    <!-- Voyant de connexion à Georeso Rip -->
                    <div class="wrapper_insider">
                        <p class="bdd_light_text">Connexion Géoreso RIP : </p>
                        <div class="bdd_light" id="georeso_rip_light"></div>
                    </div>
                    <div class="subwrapper_insider">
                        <div id="heure_georeso_rip"></div>
                        <p> - </p>
                        <div id="delais_georeso_rip"></div>
                        <div class='refresh_button' id="georesoRipRefresh">
                            <img class='refresh_image' src="public\images\refresh.svg" alt="" title="Recharge la connexion à Géoreso Rip">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
            // Modulation d'affichage : la barre de recherche n'est pas générée dans le menu de la Gestion QD
            if(!isset($_POST['submitQD'])){
        ?>

                <!-- o-header__a-navBackground -->
                <nav class="nav_search_container" id="searchContainer">
                    <div class="search_container">
                        <!--SEARCH BAR-->
                        <textarea class="textarea_search" autocomplete="off" autofocus placeholder="Rechercher" require onInput="this.parentNode.dataset.replicatedValue = this.value" name="searchBar"></textarea>

                        <div class="input_search">
                            <!--SEARCH TYPE-->
                            <select name="typeSearch" id="typeSearch" class="o-header__m-typeSearch">
                                <option value="search-byNDVIA">Ipon - Type de recherche : ND/VIA</option>
                                <option value="search-byID">Ipon - Type de recherche : ID</option>
                                <option value="search-GER">Ipon & Ger - Type de recherche : PT GER</option>
                                <option value="search-supportGER">Ipon & Ger - Type de recherche : Support GER</option>
                            </select>
                            <button class="o-header__a-search" type="submit" name="submitSearch" id="buttonSearch" >Valider</button>
                        </div>
                    </div>
                </nav>
                <!--SEARCH SUBTYPE-->
                <div id="supportGerTypeSearchContainer">
                    <select name="supportGerTypeSearch" id="supportGerTypeSearch" class="o-header__m-supportGerTypeSearch">
                        <option value="gerTypeSearch-AppuiFt">Type support : Appui-ft</option>
                        <option value="gerTypeSearch-AppuiErdf">Type support : Appui-erdf</option>
                        <option value="gerTypeSearch-Chambre">Type support : Chambre</option>
                        <option value="gerTypeSearch-Armoire">Type support : Armoire</option>
                        <option value="gerTypeSearch-Immeuble">Type support : Immeuble</option>
                    </select>
                    <!-- <input type="text" name="supportGerRefPT" id="supportGerRefPT" class="o-header__m-supportGerRefPT" placeholder="Entrer la référence PT"> -->
                </div>

    <?php
            }
        }
    ?>

</form>

<!-- Outils de navigation verticale : lié aux balises positionnées en haut et en bas de l'application -->
<section id="section_navigate" style="display:none;">
    <a id="navigate_top" href='#top_page'>⇑</a>
    <a id='navigate_bottom' href='#bottom_page'>⇓</a>
</section>


<!-- Section accueillant les règles et l'analyse -->
<section class="p-search__o-state">
    <div class="p-search__m-dataSearch"></div>
    <div class="p-search__m-dataSearch-Scoreboard"></div>
</section>

<!-- Section pour l'affichage du temps global pris pour l'xécution d'une rehcerche -->
<br>
<section class="temps_global" id="temps_global"></section>
<br>

<!-- Section accueillant les résultats de la recherche -->
<section class="p-search__o-result">
    <div class="o-rule"></div>
    <?php


        include './script/areArraysEmptyRecursive.php';
        include './script/getSaveData.php';
        include './script/saveKeyData.php';
        include "./controller/base/ControllerHeader.php";
        // include "./analyse/qd.php";
        include './view/qdManager.php';
        include "./view/historySearch.php";

    ?>
</section>

<!-- Section d'ancrage de bas de page pour la navigation verticale -->
<section id="bottom_page"></section>

<script src="public\js\switchTheme.js"></script>
<script src="public\js\searchMode.js"></script>
<script src="public\js\databaseMode.js"></script>
<script src="public\js\scrollDetection.js"></script>
<script src="public\js\pressEnterToSearch.js"></script>
<script src="public\js\loader.js"></script>
<script src="public\js\functions.js"></script>
<script src="public\js\ajax_controller.js"></script>

<?php 

    include_once "./config/test_connection_database.php";
    include_once "./analyse/compareSoftwareVersion.php";
    include_once "./script/saveInstallationLog.php";

    //include './config/refreshDatabaseConnection.php';


?>

<script src="public\js\manageDatabaseNav.js"></script>
<script>
    manageDatabase();
</script>