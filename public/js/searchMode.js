
//-----------------------------------------------//
// Pour le menu déroulant des modes de recherche //
//-----------------------------------------------//

// Récupérer le menu déroulant
const elem = document.getElementById("typeSearch");

// Récupérer le deuxieme menu déroulant
const inputSupportGERContainer = document.getElementById('supportGerTypeSearchContainer');

if(elem != null){
    // Ajouter un écouteur d'événements pour le clic sur le menu déroulant
    elem.addEventListener('click', () => {  
        // Récupérer la valeur de l'option sélectionnée
        let value = elem.options[elem.selectedIndex].value;
        // Enregistrer la valeur de l'option dans le localStorage
        localStorage.setItem('searchMode', value);

        
        // Si on a sélectionné la recherche par type support GER alors on affiche le deuxieme menu déroulant sinon, il n'apparait pas 
        if(value == "search-supportGER"){      
            inputSupportGERContainer.style.display = 'flex';
        }
        else{
            inputSupportGERContainer.style.display = 'none';
        }

        // N'afficher les checkbox de sélection des bases de données que si le mode de recherche PT-GER est sélectionné
        if(value == 'search-GER'){
            if(document.getElementById('databaseButtonChoice') != null){
                document.getElementById('databaseButtonChoice').style.opacity = 1;
                document.getElementById('databaseButtonChoice').style.zIndex = 0;
            }            
        }// Sinon, les cacher
        else{
            if(document.getElementById('databaseButtonChoice') != null){
                document.getElementById('databaseButtonChoice').style.opacity = 0;
                document.getElementById('databaseButtonChoice').style.zIndex = -999;
            }
        }
    })

}


// -------------------------------------------------


// Récupérer la valeur de l'option au lancement de l'application (celle stockée dans le localStorage)
const searchMode = localStorage.getItem('searchMode');

// On vérifie si la valeur du localStorage correspond à "search-supportGER" car si c'est la cas, on affiche aussi le menu déroulant des type de support GER
if(searchMode == "search-supportGER"){
    if(inputSupportGERContainer != null){
        inputSupportGERContainer.style.display = 'flex';
    }
}
else{
    if(inputSupportGERContainer != null){
        inputSupportGERContainer.style.display = 'none';
    }
}

if(searchMode == 'search-supportGER' || searchMode == 'search-byID' || searchMode == 'search-byNDVIA'){
    document.getElementById('databaseButtonChoice').style.opacity = 0;
    document.getElementById('databaseButtonChoice').style.zIndex = -9999;
}



// On parcourt la liste des options de la liste déroulante
for(var i, j = 0; i = elem.options[j]; j++) {
    // Si la valeur de l'option i correspond à la valeur de l'option enregistrée dans le localstorage
    if(i.value == searchMode) {
        // On applique la sélection par défaut à cette option et on quitte la boucle
        elem.selectedIndex = j;
        break;
    }
}



//-------------------------------------------------//
// Pour le menu déroulant des types de support GER //
//-------------------------------------------------//


// Récupérer le menu déroulant des types de site support
const searchModeTypeSupport = document.getElementById("supportGerTypeSearch");


// Ajouter un écouteur d'événements pour le clic sur le menu déroulant
searchModeTypeSupport.addEventListener('click', () => {  
    // Récupérer la valeur de l'option sélectionnée
    let value = searchModeTypeSupport.options[searchModeTypeSupport.selectedIndex].value;
    // Enregistrer la valeur de l'option dans le localStorage
    localStorage.setItem('searchModeTypeSupport', value);
})

// -------------------------------------------------

// Récupérer la valeur de l'option au lancement de l'application (celle stockée dans le localStorage)
const searchModeTypeSupportLocalStorage = localStorage.getItem('searchModeTypeSupport');

// On parcourt la liste des options de la liste déroulante
for(let i, j = 0; i = searchModeTypeSupport.options[j]; j++) {
    // Si la valeur de l'option i correspond à la valeur de l'option enregistrée dans le localstorage
    if(i.value == searchModeTypeSupportLocalStorage) {
        // On applique la sélection par défaut à cette option et on quitte la boucle
        searchModeTypeSupport.selectedIndex = j;
        break;
    }
}
