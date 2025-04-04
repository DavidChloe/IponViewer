
// Gère le click sur le bouton de changement de thème pour modifier l'apparence d'Ipon Viewer

// Récupérer le bouton
const modeButton = document.getElementById('header_nav_toggleLightMode');
// Récupérer l'image du bouton
const imgMode = modeButton.getElementsByTagName('img')[0];



// Ajouter un écouteur d'événements pour le clic sur le bouton
modeButton.addEventListener('click', () => {
  // Récupérer la valeur actuelle du mode (on regarde si la class du body correspond au theme sombre)
  const isDarkMode = document.body.classList.contains('dark-mode');

  // Changer la valeur du mode en modifiant la class du body
  document.body.classList.toggle('dark-mode');
  const newMode = document.body.classList.contains('dark-mode');

  // Enregistrer la valeur du mode dans le localStorage
  localStorage.setItem('isDarkMode', newMode);

  // Mettre à jour l'icon du bouton
  if(newMode){
    imgMode.src = "./public/images/lune-claire.png";
  }
  else{
    imgMode.src = "./public/images/soleil-clair.png";
  }
});


// -------------------------------------------------


// Récupérer la valeur du mode au lancement de l'application
const isDarkMode = localStorage.getItem('isDarkMode') === 'true';
document.body.classList.toggle('dark-mode', isDarkMode);

// Mettre à jour l'image du bouton
if(isDarkMode){
  imgMode.src = "./public/images/lune-claire.png";
}
else{
  imgMode.src = "./public/images/soleil-clair.png"; 
}