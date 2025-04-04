// Fonction permettant de mettre à jour l'affichage des voyants + informations de connexion aux bases de données
function manageDatabase(){

    // Récupérer les voyants de connexion aux bases de données
    let voyantIpon = document.getElementById("ipon_light");
    let voyantIponRip = document.getElementById("ipon_rip_light");
    let voyantGeoreso = document.getElementById("georeso_light");
    let voyantGeoresoRip = document.getElementById("georeso_rip_light");


    // Récupérer les éléments accueillant l'heure de connexion aux bases de données
    let heureIpon = document.getElementById("heure_ipon");
    let heureIponRip = document.getElementById("heure_ipon_rip");
    let heureGeoreso = document.getElementById("heure_georeso");
    let heureGeoresoRip = document.getElementById("heure_georeso_rip");


    // Récupérer les éléments accueillant la durée de connexion aux bases de données
    let delaisIpon = document.getElementById("delais_ipon");
    let delaisIponRip = document.getElementById("delais_ipon_rip");
    let delaisGeoreso = document.getElementById("delais_georeso");
    let delaisGeoresoRip = document.getElementById("delais_georeso_rip");

    // Récupérer des informations sauvegardées dans le localStorage
    let localStorageVoyants = [localStorage.getItem("connexionIpon"), localStorage.getItem("connexionIponRip"), localStorage.getItem("connexionGeoreso"), localStorage.getItem("connexionGeoresoRip")];
    let localStorageHeures = [localStorage.getItem("heureTestIpon"), localStorage.getItem("heureTestIponRip"), localStorage.getItem("heureTestGeoreso"), localStorage.getItem("heureTestGeoresoRip")];
    let localStorageDelais = [localStorage.getItem("delaisConnexionIpon"), localStorage.getItem("delaisConnexionIponRip"), localStorage.getItem("delaisConnexionGeoreso"), localStorage.getItem("delaisConnexionGeoresoRip")];


    // On définit la couleur de nos voyants

    // Couleur rouge
    const colorRed = "red";

    // Couleur verte
    const colorGreen = "rgb(48, 241, 77)";

    // On met nos voyants dans un tableau
    let voyants = [voyantIpon, voyantIponRip, voyantGeoreso, voyantGeoresoRip];
    let heures = [heureIpon, heureIponRip, heureGeoreso, heureGeoresoRip];
    let delais = [delaisIpon, delaisIponRip, delaisGeoreso, delaisGeoresoRip];

    // Initialisation de la variable incrémentable
    let rang = 0;




    // Pour chaque résultats de test de connexion dans tab (qui contient true si la connexion a réussi et false si elle a échoué)
    for (const key in localStorageVoyants) {

        // On consulte sa valeur
        // Si la valeur est true, alors le voyant correspondant (dans le tableau 'voyant' à i), je passe le voyant à vert
        if (localStorageVoyants[key] == 'true') {
            if(voyants[rang] != null){
                voyants[rang].style.backgroundColor = colorGreen;
            }
            
        }// Si la valeur est false, alors le voyant correspondant (dans le tableau 'voyant' à i), je passe le voyant à rouge
        else if(localStorageVoyants[key] == 'false'){
            if(voyants[rang] != null){
                voyants[rang].style.backgroundColor = colorRed;
            }
        }
        else{// Par défaut, la couleur des voyants sera rouge si la valeur n'est ni true ni false
            if(voyants[rang] != null){
                voyants[rang].style.backgroundColor = colorRed;
            }
        }
        // On incrémente pour passer au voyant suivant
        rang++;
    }


    // Initialisation de la variable incrémentable
    rang = 0;

    // Pour chaque heure de test de connexion dans localStorageHeures 
    for (const key in localStorageHeures) {

        heures[rang].innerHTML = localStorageHeures[key];
        rang++;
    }


    // Réinitialisation de la variable incrémentable
    rang = 0;


    // Pour chaque durée de test de connexion dans localStorageDelais
    for (const key in localStorageDelais) {

        // Mise au format XX s XXXXX ms
        const secondes = Math.trunc(localStorageDelais[key] / 1000);
        const millisecondes = Math.trunc(localStorageDelais[key] % 1000);
        // Intégration au html
        delais[rang].innerHTML = `${secondes} s ${millisecondes} ms`;
        rang++;
    }
}