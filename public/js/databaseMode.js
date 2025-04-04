// Récupérer les boutons radio ayant le champs 'name' correspondant à 'database'
const radio = document.getElementsByName("database");

// Récupérer le nombre de boutons radio dans une variable 
let nbRadio = radio.length;


// Fonction s'exécutant à chaque appel
function checkRadio(){

    // Initialiser la variable
    let optionSelectionnee = 'IPON';

    // Pour chaque bouton radio 
    for (let i = 0; i < nbRadio; i++) {
        // Si le radio i est checker
        if (radio[i].checked) {
            // Récupérer sa valeur
            optionSelectionnee = radio[i].value;
            // Stocker la valeur dans le localStorage (soit 'RIP', soit 'IPON')
            localStorage.setItem('radioChecked', optionSelectionnee);

            console.log('bouton radio : ' + radio[i].value + ' est check');
        }
        // Sinon, afficher un message dans la console
        // else{
        //     console.log('Erreur : aucun bouton radio checké. Impossible de sélectionner la base de données.')
        // }
    }
}

// ------------------------------------

//Execution au lancement de l'application

// Pour chaque bouton radio
for (let i = 0; i < nbRadio; i++) {
    // Placer l'écouteur d'évènement de changement, couplé à la fonction
    radio[i].addEventListener('change', checkRadio);
}

// Pour chaque bouton radio
for (let i = 0; i < nbRadio; i++) {
    // Si sa valeur correspond à celle du localStorage
    if(radio[i].value == localStorage.getItem('radioChecked')){
        // Checker ce bouton radio
        radio[i].checked = true;
    }
}