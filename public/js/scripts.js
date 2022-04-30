////////////////////////////////////////////
// affichage calendrier dashboard
/////////////////////////////////////////////

// let rdv = document.getElementById('rdvJs').dataset.choise
// console.log("rdv")
    // console.log(rdv)
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'fr',
            timeZone: 'Europe/Paris',
            headerToolbar: {
              left: 'prev,next today',
              center: 'title',
              right: 'dayGridMonth,timeGridWeek,timeGridDay',
              },
            //events: rdv,
          });
        calendar.render();
        });

//         const json = '{"result":true, "count":42}';
// const obj = JSON.parse(json);  <- truc a voir

// console.log(obj.count);
// // expected output: 42

// console.log(obj.result);
// // expected output: true
