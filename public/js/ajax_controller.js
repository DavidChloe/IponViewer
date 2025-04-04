
// Controller Ajax (jQuery) pour le rafraichissement des connexions aux bases de données par clic sur le bouton de rafraichissement

$(document).ready(function() {

    
    // Au clic sur le bouton permettant de lancer l'éxécution du rafrachissement de la connexion à la base de données Ipon
    $('#iponRefresh').click(function() {

        // Changer la classe su bouton
        // Il devient un spinner de chargement
        $('#iponRefresh').removeClass('refresh_image').addClass('refresh_image_roller');
        $('#ipon_light').css("background-color", "orange");
        // La requête Ajax
        $.ajax({
            // Url du fichier php à éxécuter
            url: './config/refreshDatabaseConnection.php',
            // Méthode d'éxécution 
            method: 'POST',
            // On préviens que le retour sera au format JSON
            dataType: 'json',
            // Paramètres 
            data: {
                param1:'Ipon'
            },
            // Actions en cas de succès
            success: function(response) {
                
                // Dans le script, on récupère nos données sous la forme d'un tableau à 3 rangs
                // On vérifie que le tableau n'est pas vide
                if (Object.keys(response).length !== 0) {

                    // Pour chaque rang identifié par sa clef établie 
                    for (const key in response) {
                        // Sauvegarde dans le localStrorage pour mise à jour de l'affichage (voyants de connexion aux bases de données)
                        updateLocalStorage(key, response[key]);

                    }
                    console.log('Refresh successful !');
                }
                else{
                    console.log('Refresh not successful : erreur lors de la réception des données !');
                    alert("Erreur : Impossible de râfraichir la connexion à la base de données.");
                }
                
                $('#iponRefresh').removeClass('refresh_image_roller').addClass('refresh_image');
                $('#ipon_light').removeAttr("style");

                // Appelle de la fonction pour mettre à jour l'affichage avec les données du localStorage
                manageDatabase();

            },
            // Action en cas d'échec
            error: function(xhr, status, error) {

                console.log(status + ': ' + error); // Afficher l'erreur dans la console en cas d'échec

                $('#iponRefresh').removeClass('refresh_image_roller').addClass('refresh_image');
                $('#ipon_light').removeAttr("style");
                
                // Mettre le voyant en rouge 
                setDefaultLocalStorage('Ipon');
                manageDatabase();
                
                alert("Échec de la connexion à la base de données Ipon. Vérifier votre connexion Cactus.");

                
                
            }
        });
    });

    // Au clic sur le bouton permettant de lancer l'éxécution du rafrachissement de la connexion à la base de données Ipon Rip
    $('#iponRipRefresh').click(function() {

        // Changer la classe su bouton
        // Il devient un spinner de chargement
        $('#iponRipRefresh').removeClass('refresh_image').addClass('refresh_image_roller');
        $('#ipon_rip_light').css("background-color", "orange");

        // La requête Ajax
        $.ajax({
            // Url du fichier php à éxécuter
            url: './config/refreshDatabaseConnection.php',
            // Méthode d'éxécution 
            method: 'POST',
            // On préviens que le retour sera au format JSON
            dataType: 'json',
            // Paramètres 
            data: {
                param1:'IponRip'
            },
            // Actions en cas de succès
            success: function(response) {
                
                // Dans le script, on récupère nos données sous la forme d'un tableau à 3 rangs
                // On vérifie que le tableau n'est pas vide
                if (Object.keys(response).length !== 0) {

                    // Pour chaque rang identifié par sa clef établie 
                    for (const key in response) {
                        // Sauvegarde dans le localStrorage pour mise à jour de l'affichage (voyants de connexion aux bases de données)
                        updateLocalStorage(key, response[key]);
                    }
                    console.log('Refresh successful !');
                }
                else{
                    console.log('Refresh not successful : erreur lors de la réception des données !');
                    alert("Erreur : Impossible de râfraichir la connexion à la base de données.");
                }
                
                $('#iponRipRefresh').removeClass('refresh_image_roller').addClass('refresh_image');
                $('#ipon_rip_light').removeAttr("style");

                // Appelle de la fonction pour mettre à jour l'affichage avec les données du localStorage
                manageDatabase();

            },
            // Action en cas d'échec
            error: function(xhr, status, error) {

                console.log(status + ': ' + error); // Afficher l'erreur dans la console en cas d'échec

                $('#iponRipRefresh').removeClass('refresh_image_roller').addClass('refresh_image');
                $('#ipon_rip_light').removeAttr("style");

                // Mettre le voyant en rouge 
                setDefaultLocalStorage('IponRip');
                manageDatabase();

                alert("Échec de la connexion à la base de données Ipon Rip. Vérifier votre connexion Cactus.");
            }
        });
    });

    // Au clic sur le bouton permettant de lancer l'éxécution du rafrachissement de la connexion à la base de données Géoreso
    $('#georesoRefresh').click(function() {

        // Changer la classe su bouton
        // Il devient un spinner de chargement
        $('#georesoRefresh').removeClass('refresh_image').addClass('refresh_image_roller');
        $('#georeso_light').css("background-color", "orange");

        // La requête Ajax
        $.ajax({
            // Url du fichier php à éxécuter
            url: './config/refreshDatabaseConnection.php',
            // Méthode d'éxécution 
            method: 'POST',
            // On préviens que le retour sera au format JSON
            dataType: 'json',
            // Paramètres 
            data: {
                param1:'Georeso'
            },
            // Actions en cas de succès
            success: function(response) {
                
                // Dans le script, on récupère nos données sous la forme d'un tableau à 3 rangs
                // On vérifie que le tableau n'est pas vide
                if (Object.keys(response).length !== 0) {

                    // Pour chaque rang identifié par sa clef établie 
                    for (const key in response) {
                        // Sauvegarde dans le localStrorage pour mise à jour de l'affichage (voyants de connexion aux bases de données)
                        updateLocalStorage(key, response[key]);
                    }
                    console.log('Refresh successful !');
                }
                else{
                    console.log('Refresh not successful : erreur lors de la réception des données !');
                    alert("Erreur : Impossible de râfraichir la connexion à la base de données.");
                }
                
                $('#georesoRefresh').removeClass('refresh_image_roller').addClass('refresh_image');
                $('#georeso_light').removeAttr("style");

                // Appelle de la fonction pour mettre à jour l'affichage avec les données du localStorage
                manageDatabase();

            },
            // Action en cas d'échec
            error: function(xhr, status, error) {

                console.log(status + ': ' + error); // Afficher l'erreur dans la console en cas d'échec

                $('#georesoRefresh').removeClass('refresh_image_roller').addClass('refresh_image');
                $('#georeso_light').removeAttr("style");

                // Mettre le voyant en rouge 
                setDefaultLocalStorage('Georeso');
                manageDatabase();

                alert("Échec de la connexion à la base de données Géoreso. Vérifier votre connexion Cactus.");
            }
        });
    });

    // Au clic sur le bouton permettant de lancer l'éxécution du rafrachissement de la connexion à la base de données Géoreso Rip
    $('#georesoRipRefresh').click(function() {

        // Changer la classe du bouton
        // Il devient un spinner de chargement
        $('#georesoRipRefresh').removeClass('refresh_image').addClass('refresh_image_roller');
        $('#georeso_rip_light').css("background-color", "orange");

        // La requête Ajax
        $.ajax({
            // Url du fichier php à éxécuter
            url: './config/refreshDatabaseConnection.php',
            // Méthode d'éxécution 
            method: 'POST',
            // On préviens que le retour sera au format JSON
            dataType: 'json',
            // Paramètres 
            data: {
                param1:'GeoresoRip'
            },
            // Actions en cas de succès
            success: function(response) {
                
                // Dans le script, on récupère nos données sous la forme d'un tableau à 3 rangs
                // On vérifie que le tableau n'est pas vide
                if (Object.keys(response).length !== 0) {

                    // Pour chaque rang identifié par sa clef établie 
                    for (const key in response) {
                        // Sauvegarde dans le localStrorage pour mise à jour de l'affichage (voyants de connexion aux bases de données)
                        updateLocalStorage(key, response[key]);
                    }
                    console.log('Refresh successful !');
                }
                else{
                    console.log('Refresh not successful : erreur lors de la réception des données !');
                    alert("Erreur : Impossible de râfraichir la connexion à la base de données.");
                }
                

                $('#georesoRipRefresh').removeClass('refresh_image_roller').addClass('refresh_image');
                $('#georeso_rip_light').removeAttr("style");
                // Appelle de la fonction pour mettre à jour l'affichage avec les données du localStorage
                manageDatabase();

            },
            // Action en cas d'échec
            error: function(xhr, status, error) {

                console.log(status + ': ' + error); // Afficher l'erreur dans la console en cas d'échec

                $('#georesoRipRefresh').removeClass('refresh_image_roller').addClass('refresh_image');
                $('#georeso_rip_light').removeAttr("style");

                // Mettre le voyant en rouge 
                setDefaultLocalStorage('GeoresoRip');
                manageDatabase();

                alert("Échec de la connexion à la base de données Géoreso Rip. Vérifier votre connexion Cactus.");
            }
        });
    });
});