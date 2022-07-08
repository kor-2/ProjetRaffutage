////////////////////////////////////////////
// affichage calendrier dashboard
/////////////////////////////////////////////

document.addEventListener('DOMContentLoaded', function() {

 


  // Element pour la modal
  
  let modal = document.getElementById('modalInfo')
  let inputClient = document.getElementById('client')
  let inputEmail = document.getElementById('email')
  let inputTel = document.getElementById('tel')
  let inputDetail = document.getElementById('details')
  let inputDebut = document.getElementById('debut')
  let inputTotal = document.getElementById('total')
  
  // recuperation des donnée de la table Prestation 
  // retourne une chaine de caratère
  let rdv = document.getElementById("rdvJs").dataset.choice
  // analyse une chaine de caractère et la transforme en JSON 
  let creneauLibre = JSON.parse(rdv)
      
  let calendarEl = document.getElementById('calendar');
  let calendar = new FullCalendar.Calendar(calendarEl, {
    // donne le JSON a l'agenda
    events: creneauLibre,
    // configuration de l'affichage de full calendar
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
    },
    
    locale: 'fr', // la langue
    nowIndicator: true, // place un marqueur sur le jour et l'heure actuelle
    weekNumbers: true, // affiche les numéros de semaine
    businessHours: { //grise les jours et heures non travaillé
      daysOfWeek: [ 1, 2, 3, 4, 5, 6 ],
      startTime: '09:00', 
      endTime: '18:00', 
    },
    navLinks: true, // click sur un jour puis affiche le jour en question
    // fonction pour afficher dans un modal les détails des prestation réservées 
    eventClick: function(presta){
        
      let valuePresta = presta.event;
      
        if (valuePresta.extendedProps.client){

          modal.classList.remove('hidden')
          let nomClient = valuePresta.extendedProps.client
          let valueEmail = valuePresta.extendedProps.email
          let valueTel = valuePresta.extendedProps.tel
          let valueDetails = valuePresta.extendedProps.details
          let valueDebut = new Intl.DateTimeFormat('fr-FR',{ dateStyle: 'long', timeStyle: 'short' }).format(valuePresta.start)
          

          inputClient.innerText = nomClient
          inputDebut.innerText = valueDebut
          inputEmail.innerText = valueEmail
          inputTel.innerText = valueTel
          let sumCt = 0;
          valueDetails.forEach((detail) => {
            
            inputDetail.innerHTML += "<tr><td>"+detail.type+"</td><td>"+ detail.nbCouteau+"</td></tr>";
          
            sumCt = sumCt + parseInt(detail.nbCouteau)
          });
          inputTotal.innerText =  sumCt;


        }else{
          alert('Personne n\'a réservé ce créneau.')
        }
      },
    })
  calendar.render();

  // la modal en display none
  let btnHide = document.getElementById('hide')

    btnHide.addEventListener('click', function(){
  
      inputDetail.innerHTML = ""
      inputTotal.innerText =  "";
      modal.classList.add('hidden')
  })

})

