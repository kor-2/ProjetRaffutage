////////////////////////////////////////////
// affichage calendrier dashboard
/////////////////////////////////////////////

window.onload = function(){


  let rdv = document.getElementById("rdvJs").dataset.choice
  let test = JSON.parse(rdv)
  console.log(test)
  console.log(":(")
  // console.log(rdv)
      
  let calendarEl = document.getElementById('calendar');
  let calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'timeGridWeek',
      
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay',
        },
      events: test,
      locale: 'fr',
      timeZone: 'Europe/Paris',
    })
  calendar.render();
}
