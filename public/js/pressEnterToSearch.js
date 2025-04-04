
// Gère l'appui de la touche Entrer du clavier pour lancer la recherche

// Obtenez une référence au bouton de soumission
let bouton = document.getElementById("buttonSearch");

// Ajoutez un écouteur d'événements "keydown" à tous les éléments de formulaire
document.querySelectorAll('form input, form select, form textarea').forEach(function(elem) {
  elem.addEventListener('keydown', function(event) {
    // Vérifiez si la touche appuyée est la touche "Entrée"
    if (event.keyCode === 13) {
      // Empêchez le comportement par défaut de la touche "Entrée" sur les éléments de formulaire
      event.preventDefault();
      // Appelez le gestionnaire d'événements de clic pour le bouton de soumission
      bouton.click();
    }
  });
});