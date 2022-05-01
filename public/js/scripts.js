////////////////////////////////////////////
// affichage calendrier dashboard
/////////////////////////////////////////////

window.onload = function(){


  let rdv = document.getElementById("rdvJs").dataset.choice
  // analyse une chaine de caractère et la transforme en JSON 
  let test = JSON.parse(rdv)
      
  let calendarEl = document.getElementById('calendar');
  let calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'fr',
      timeZone: 'Europe/Paris',
      headerToolBar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay',
        },
      events: test,
      
      
    })
  calendar.render();
}
