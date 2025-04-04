
// Fonction de mise à jour du localStorage 
// key : clé identifiant une données du localStorage
// value : valeur à stocker dans le loclaStorage
function updateLocalStorage(key, value) {

    // Si la clé existe déjà dans le localStorage
    if (localStorage.getItem(key)) {
        // Mettre à jour la valeur
        localStorage.setItem(key, value);
    } else {
        // Sinon créer la paire clé/valeur avec les paramètres de la fonction
        localStorage.setItem(key, value);
    }
}

// Permet de mettre par défaut les informations du localStorage d'un voyant de connexion aux base de données (met la couleur à rouge)
// database : le string comportant le nom de la base de donnée. Est utilisé pour créer le nom de la clé ciblant le voyant associé à la base de données. 
function setDefaultLocalStorage(database){
    
    // Création de la clé d'identification dans le localStorage
    let connexion = "connexion"+database;
    let heure = "heureTest"+database;
    let delais = "delaisConnexion"+database;

    // Créer une instance de l'objet Date
    let date = new Date();

    // Obtenir les composants de l'heure
    let heures = date.getHours();
    let minutes = date.getMinutes();
    let secondes = date.getSeconds();

    // Formater l'heure
    heures = heures < 10 ? "0" + heures : heures;
    minutes = minutes < 10 ? "0" + minutes : minutes;
    secondes = secondes < 10 ? "0" + secondes : secondes;

    // Concaténer les composants pour obtenir l'heure au format hh:mm:ss
    let heureActuelle = heures + ":" + minutes + ":" + secondes;

    // Récupérer dans un tableau les paires clé/valeur
    let tab = 
    {
        [connexion] : 'false', 
        [heure] : heureActuelle,
        [delais] : "1000",
    };
    
    // Pour chaque paire
    for (const key in tab) {
        // Sauvegarde dans le localStrorage pour mise à jour de l'affichage (voyants de connexion aux bases de données)
        updateLocalStorage(key, tab[key]);
        console.log('clé '+key+' -> valeur : '+tab[key]);
        
    }
}