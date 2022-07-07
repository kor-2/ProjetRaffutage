//////////////////////////////////////////////////////
// affichage model suppression de compte
//////////////////////////////////////////////////////

//afficher modal
let btnShow = document.getElementById('showModal')

let modal = document.getElementById('modal')
btnShow.addEventListener('click', function(){
  modal.classList.remove('hidden')
})
// cacher modal

let btnHide = document.getElementById('hide')

btnHide.addEventListener('click', function(){
  modal.classList.add('hidden')
})