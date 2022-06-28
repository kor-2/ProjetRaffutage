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