
// Gère l'affichage du module de navigation vers le haut ou vers le bas

// Sélectionne l'élément dont on souhaite observer les mutations
const targetNode = document.documentElement;

// Options pour le MutationObserver (on veut détecter les modifications de la taille de la scrollbar)
const config = { attributes: true, childList: true, subtree: true };

// Récupération de la section contenant les flèche de raccourci vers le bas et vers le haut
let smoothScrollButtons = document.getElementById('section_navigate');

// Récupération de toute la partie comprise entre les balises html de la page
let html = document.getElementsByTagName('html');

// Création de l'objet MutationObserver
const observer = new MutationObserver(function(mutationsList) {
    for(const mutation of mutationsList) {
        // Si la taille du document a été modifiée
        if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
            // Vérifie si la scrollbar est visible
            if (targetNode.scrollHeight > targetNode.clientHeight && smoothScrollButtons != null) {
                // console.log("La scrollbar est visible");
                smoothScrollButtons.style.display = 'flex';
            } else {
                // console.log("La scrollbar est cachée");
                smoothScrollButtons.style.display = 'none';
            }
        }
    }
});

// Démarrage de l'observation avec la configuration spécifiée
observer.observe(targetNode, config);
