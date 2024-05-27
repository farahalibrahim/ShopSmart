//swiper js
var swiper = new Swiper(".home", {
  spaceBetween: 120,
  centeredSlides: true,
  autoplay: {
    delay: 5000,
    disableOnInteraction: false,
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});
// let loginform = document.querySelector('.login-form');
// document.querySelector('#loginbtn').onclick = () => {
//   loginform.classList.toggle('active');
// }