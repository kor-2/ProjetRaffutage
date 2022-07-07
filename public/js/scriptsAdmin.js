////////////////////////////////////////////
// affichage calendrier dashboard
/////////////////////////////////////////////

document.addEventListener('DOMContentLoaded', function() {

  // recuperation des donnée de la table Prestation 
  // retourne une chaine de caratère exemple ->[{"title":"Cr\u00e9neau libre","id":1,"start":"2022-05-02 09:00:00","end":"2022-05-02 10:00:00","backgroundColor":"#009933"}]


  let rdv = document.getElementById("rdvJs").dataset.choice
  let modal = document.getElementById('modalInfo')
  let inputClient = document.getElementById('client')
  let inputEmail = document.getElementById('email')
  let inputTel = document.getElementById('tel')
  let inputDetail = document.getElementById('details')
  let inputDebut = document.getElementById('debut')
  let inputTotal = document.getElementById('total')


  // analyse une chaine de caractère et la transforme en JSON 
  let creneauLibre = JSON.parse(rdv)
      
  let calendarEl = document.getElementById('calendar');
  let calendar = new FullCalendar.Calendar(calendarEl, {
    // configuration de l'affichage de full calendar
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
    },
    events: creneauLibre,
    locale: 'fr',
    nowIndicator: true,
    weekNumbers: true,
    businessHours: {
      daysOfWeek: [ 1, 2, 3, 4, 5, 6 ],
      startTime: '09:00', 
      endTime: '18:00', 
    },
    navLinks: true,
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

