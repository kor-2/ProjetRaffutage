////////////////////////////////////////////
// affichage calendrier dashboard
/////////////////////////////////////////////

document.addEventListener('DOMContentLoaded', function() {

    // recuperation des donnée de la table Prestation 
    // retourne une chaine de caratère
    let rdv = document.getElementById("rdvJs").dataset.choice
    // analyse une chaine de caractère et la transforme en JSON 
    let creneauLibre = JSON.parse(rdv)
    let calendarEl = document.getElementById('calendar');
    let calendar = new FullCalendar.Calendar(calendarEl, {
      // configuration de l'affichage de full calendar
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,listMonth'
      },
      // events : attend un json ou est stocké les créneaux
      events: creneauLibre,
      locale: 'fr',
      nowIndicator: true,
      businessHours: {
        daysOfWeek: [ 1, 2, 3, 4, 5, 6 ],
        startTime: '09:00', 
        endTime: '18:00', 
      },
      hiddenDays:[0],
      eventClick: function(presta){
        
        let valuePresta = presta.event;
        if (!valuePresta.extendedProps.code) {
          let input = document.getElementById("rdvJs")
          let show = document.getElementById("selection")
          // envoi de l'id de la prestation dans input hidden 
          let valeur = valuePresta.id
          input.value = valeur

          //envoi de la date la prestation dans le h3 pour l'affichage
          let debut = valuePresta.start
          let debutFormat = new Intl.DateTimeFormat('fr-FR',{ dateStyle: 'long', timeStyle: 'short' }).format(debut)
          show.innerText = debutFormat
          
        }else{
          alert("Le créneau est indisponible")
        }
      },
    })
    calendar.render();
  })

  //////////////////////////////////////////////////////
  // menu burger
  //////////////////////////////////////////////////////

let btnBurger = document.getElementById('btnBurger')
btnBurger.addEventListener('click', function(){

    let btnMenu = document.querySelectorAll('.btnMenu')

    btnMenu.forEach(function(btnM){

        btnM.classList.toggle('hidden')
    })
    
    if (document.getElementById('burger').classList.contains('fa-bars')) {
        
        document.getElementById('burger').classList.replace('fa-bars','fa-xmark')
    }else{
        document.getElementById('burger').classList.replace('fa-xmark','fa-bars')
    }

    
})

if (window.screen.width > 576) {
  let btnMenu = document.querySelectorAll('.btnMenu')
  btnMenu.forEach(function(btnM) {
    
    btnM.classList.remove('hidden')
    document.getElementById('burger').classList.replace('fa-bars','fa-xmark')
  });
}

//////////////////////////////////////////////////////
// map leaflet
//////////////////////////////////////////////////////

let map = L.map('map').setView([48.910595, 7.148211], 16);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
}).addTo(map);
var marker = L.marker([48.910595, 7.148211]).addTo(map);
marker.bindPopup("<b>Adresse</b><br>20 rue des Près 67320 Thal-Drulingen").openPopup();

//////////////////////////////////////////////////////
// affichage model suppression de compte
//////////////////////////////////////////////////////


let btnShow = document.getElementById('showModal')

btnShow.addEventListener('click', function(){
  let modal = document.getElementById('modal')
  console.log(modal);
})