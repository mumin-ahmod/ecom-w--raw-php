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
let bigImage = document.querySelector('.quick-view .box .image-container .big-image img');
let smallImage = document.querySelectorAll('.quick-view .box .image-container .small-image img');

smallImage.forEach(images =>{
   images.onclick = () =>{
      src = images.getAttribute('src');
      bigImage.src = src;
   }
});