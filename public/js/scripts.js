////////////////////////////////////////////
// affichage calendrier dashboard
/////////////////////////////////////////////

window.onload = function(){

  // recuperation des donnée de la table Prestation 
  // retourne une chaine de caratère exemple ->[{"title":"Cr\u00e9neau libre","id":1,"start":"2022-05-02 09:00:00","end":"2022-05-02 10:00:00","backgroundColor":"#009933"}]


  let rdv = document.getElementById("rdvJs").dataset.choice
  console.log(rdv)


  // analyse une chaine de caractère et la transforme en JSON 
  let creneauLibre = JSON.parse(rdv)
      
  let calendarEl = document.getElementById('calendar');
  let calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    locale: 'fr',
    timeZone: 'Europe/Paris',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay',
      },
    events: creneauLibre,
      
      
    })
  calendar.render();
}