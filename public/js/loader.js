
// Gère l'affichage du loader. Il n'apparait que lorsque le navigateur est en mode chargement.

window.addEventListener('DOMContentLoaded', function() {
  // Récupération de l'élément 
  const loader = document.getElementById('scene');

  // Ajouter une transition d'opacité de 0,4 secondes
  loader.style.transition = 'opacity 0.4s ease-in-out';

  setTimeout(function() {
    // Masquer le loader après 800ms d'execution après la fin du chargement
    loader.style.opacity = 0;
    loader.style.zIndex = -3;
    loader.style.display = 'none';
  }, 800);

});