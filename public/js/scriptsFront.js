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
      weekNumbers: true,
      businessHours: {
        daysOfWeek: [ 1, 2, 3, 4, 5, 6 ],
        startTime: '09:00', 
        endTime: '18:00', 
      },
      hiddenDays:[0],

      eventClick: function(presta){
        
        var valuePresta = presta.event;
        if (!valuePresta.extendedProps.code) {
          let input = document.getElementById("rdvJs")

          //var valeur = valuePresta.extendedProps.phpObj
          var valeur = valuePresta.id
          input.value = valeur
          console.log(valeur)
          
        }else{
          alert("Le créneau est indisponible")
        }
      },
    })
    calendar.render();
  })
  