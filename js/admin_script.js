let profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () =>{
    profile.classList.toggle('active');
    navbar.classList.remove('active');

}

let navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () =>{
    navbar.classList.toggle('active');
    profile.classList.remove('active');

}

window.onscroll = () =>{
    profile.classList.remove('active');
    navbar.classList.remove('active');

}

//// function to swap images with sub image in admin product updater
let mainImage = document.querySelector('.update-product .image-container .main-image img');
let subImages = document.querySelectorAll('.update-product .image-container .sub-image img');

subImages.forEach(images =>{
   images.onclick = () =>{
      src = images.getAttribute('src');
      mainImage.src = src;
   }
});