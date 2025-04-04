$(document).ready(function () {
  // TYPESEARCH = ND-VIA
  // When the ND-VIA send by an user has a accountclient and a repertory number link to it, detect why information the user want to search and display the search
  $(".o-result__a-typeND").click(function () {
    $(".o-result__m-containerClient").addClass("hidden");
    $(".o-result__m-containerND").removeClass("hidden");
  });

  $(".o-result__a-typeClient").click(function () {
    $(".o-result__m-containerND").addClass("hidden");
    $(".o-result__m-containerClient").removeClass("hidden");
  });

  // TYPE SEARCH = SEVERAL
    // Track the change of type search for search-several. If this option isn't selected, then hide the choice between ID and ND
    if ($("#typeSearch").val() == "search-several") {
      $(".o-header__m-containerSeveral").show();
    } else {
      $(".o-header__m-containerSeveral").hide();
    }
  $("#typeSearch").change(function () {
    if ($(this).val() == "search-several") {
      $(".o-header__m-containerSeveral").show();
    } else {
      $(".o-header__m-containerSeveral").hide();
    }
  });

  $(".o-result__a-typeClient").click(function () {
    $( ".o-result__a-typeClient" ).toggleClass( "o-result__a-resultType--select" );
    $( ".o-result__a-typeND" ).removeClass( "o-result__a-resultType--select" )
  });

  $(".o-result__a-typeND").click(function () {
    $( ".o-result__a-typeND").toggleClass( "o-result__a-resultType--select");
    $( ".o-result__a-typeClient" ).removeClass( "o-result__a-resultType--select" );
  });


   // If click on plus image (several search), then add a now search
   $(".o-header__a-more").click(function () {
    $("o-header__m-formSearch").append("<nav class='o-header__a-writeNav'></nav>");
    $("o-header__a-writeNav").append("Hallo");
    alert("test")
  });

  // ERROR MESSAGE
  // If an alert is already there, then collect all alert and display it in on message
  // If class exists
  if ($(".o-alert")[0]) {
    // Create an empty array to add all text alert
    var text = [];
    // For all alert text, add it to the array text
    $('.o-alert').each(function () {      
      text.push($(this).text());
    });
    // Remove all alert display on the page
    $(".o-alert").remove();
    // Create a new alert and add it all text separated by \n
    var newElement = "<div class='o-alert'>";
    text.forEach(element => {
      newElement += element;
      newElement += "<br/><br/>";
    });
    newElement += "</div>";
    $("body").append(newElement);

    // For all alert, display a cross to delete the pop up
    // Create the element
    $(".o-alert").append("<button class='o-alert__close'>X</button>");
    // If the user clicks on this buttton, the pop up is removed
    $(".o-alert__close").click(function () {
      $(".o-alert").remove();
    });
  };


  // RULE MESSAGE
  // If an alert is already there, then collect all alert and display it in on message
  // If class exists
  if ($(".o-rule")[0]) {
    $( ".o-rule__m-container" ).appendTo( ".o-rule" );
  };
  // For all alert, display a cross to delete the pop up
  // Create the element
  // $(".o-alert").append("<button class='o-alert__close'>X</button>");
  // // If the user clicks on this buttton, the pop up is removed
  // $(".o-alert__close").click(function () {
  //   $(".o-alert").remove();
  // });

  // DATA SEARCH & DATA STATE & 
  $( ".o-result__m-dataSearch" ).appendTo( ".p-search__m-dataSearch" );
  $( ".o-result__a-resultDB" ).appendTo( ".p-search__m-dataSearch-Scoreboard" );
  

  // SHORTCUT FORM
  // NB : doesn't work
  // Execute form by pressing enter
  $(document).ready(function () {
    $('input').keyup(function (event) {
      if (event.which === 13) {
        event.preventDefault();
        $('o-header__m-formSearch').submit();
      }
    });
  });
});






// Execution au lancement de l'application
$(document).ready(function () {
  var numPage = 0;
  
  // Si c'est la première fois que l'application est lancée, le localStorage sera vide. On positionnera donc sa valeur à 15 par défaut au début. Cela signifie 
  if(!localStorage.getItem('nbLigneParPage')){
    localStorage.setItem('nbLigneParPage', 15);
  }

  let nbLigneParPage = localStorage.getItem('nbLigneParPage');

  // On execute la fonction une première fois au démarrage
  showForPage(numPage, nbLigneParPage);

  // Récupérer le menu déroulant
  const elem = document.getElementById("row_select");

  //elem.selectedIndex = 2;
  if(elem != null){
  
    for(var i, j = 0; i = elem.options[j]; j++) {
      // Si la valeur de l'option i correspond à la valeur de l'option enregistrée dans le localstorage
      if(i.value == localStorage.getItem('nbLigneParPage')) {
          // On applique la sélection par défaut à cette option et on quitte la boucle
          elem.selectedIndex = j;
          break;
      }
    }
  
    // Ajouter un écouteur d'événements pour le clic sur le menu déroulant
    elem.addEventListener('change', () => {  
      // Récupérer la valeur de l'option sélectionnée
      nbLigneParPage = elem.options[elem.selectedIndex].value;
      localStorage.setItem('nbLigneParPage', nbLigneParPage);
      showForPage(numPage, nbLigneParPage);
        
    })
  }
  
  
  
  let monInput = document.getElementById('numPageChoice');

  if(monInput != null){
    // On place un écouteur d'évènement sur la saisie de l'input number
    monInput.addEventListener("input", function() {
      // On place un écouteur d'évènement sur le clavier
      monInput.addEventListener("keydown", (event) => {
        // Si la touche Entrée ezst pressée on execute la fonction 
        if (event.key === "Enter") {
          showForPage(monInput.value, nbLigneParPage);
        }
      });
    });
  }
  



  // Cette partie applique le même principe pour les boutons suivant et précédents en s'appuyant sur les class (codé avec jQuery)
  $(".pagination-btn").click(function () {
    if (!$(this).hasClass('disabled')) {
      var numPage = parseInt($('.pagination').data("page"));
      numPage = numPage + ($(this).hasClass('prev') ? -1 : 1);
      showForPage(numPage, nbLigneParPage);
    }
  });  

});


//fonction QD
function showForPage(numPage, nbLigneParPage) {
  //déclaration et paramétrage variable 
  let maxPage = nbLigneParPage;
   
  var totalValue = $('.pagination tbody').children('.o-result__row').length;
  var totalPage = (totalValue % maxPage == 0) ? totalValue/maxPage : parseInt(totalValue/maxPage) + 1;

  // Condition pour éviter un bug. En effet, on ne peut pas afficher des pages qui n'existent pas. Le nombre de page se trouve entre 1 en 'totalPage'
  if(numPage <= 0){
    numPage = 1;
  }
  else if (numPage > totalPage){
    numPage = totalPage;
  }

  // Comme la fonction est éxecutée au lancement de l'application, la première fois on a pas de données à afficher et on ne pourra pas accéder 
  // à certains éléments puisqu'ils n'ont pas étaient affichés donc on a un message d'erreur. Cette condition permet d'éviter ca. 
  if(totalPage > 0){
    let pageLabel = document.getElementById('labelPageChoice');
    if(pageLabel != null){
      pageLabel.innerHTML = ' / '+totalPage;
    }
    
    let inputNumber = document.getElementById('numPageChoice');
    
    if (inputNumber) {
      inputNumber.value = numPage;
    }
    
  }


  //désactivation bouton prev 
  if (numPage === 1) {
    $('.pagination-btn.prev').addClass('disabled');
  }
  else {
    $('.pagination-btn.prev').removeClass('disabled');
    
  }

  //désactivation bouton suivant 
  if (numPage === totalPage) {
    $('.pagination-btn.next').addClass('disabled');
  }
  else {
    $('.pagination-btn.next').removeClass('disabled');
    
  }

  // Affichage des lignes par page en fonction de l'option sélectionnée
  $('.pagination').data("page", numPage);
  $('.pagination tbody').children('.o-result__row').hide();
  for (let i = maxPage*(numPage-1); i < maxPage*numPage; i++) {
    $('.pagination tbody').children('.o-result__row').eq(i).show();
  }

  //affichage info pages 
  $('.pagination-page').html((numPage) + "/" + totalPage);
  $('.ligne').html(totalValue + " Lignes chargées");
    
}

function alerte_chargement(){
  alert("Chargement");
}

