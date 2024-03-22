/*=============== SHOW MENU ===============*/
const navMenu = document.getElementById('nav-menu'),
    navToggle = document.getElementById('nav-toggle'),
    navClose = document.getElementById('nav-close')

/*===== MENU SHOW =====*/
/* Validate if constant exists */
if (navToggle) {
    navToggle.addEventListener('click', () => {
        navMenu.classList.add('show-menu')
    })
}

/*===== MENU HIDDEN =====*/
/* Validate if constant exists */
if (navClose) {
    navClose.addEventListener('click', () => {
        navMenu.classList.remove('show-menu')
    })
}


/*=============== REMOVE MENU MOBILE ===============*/
const navLink = document.querySelectorAll('.nav-link')

const linkAction = () => {
    const navMenu = document.getElementById('nav-menu')
    // When we click on each nav-link, we remove the show-menu class
    navMenu.classList.remove('show-menu')
}
navLink.forEach(n => n.addEventListener('click', linkAction))


/*=============== MODAL ===============*/
const modal = document.getElementById('modal');
const modal_open = document.getElementById('modal-btn');
const modal_close = document.getElementById('modal-btn-close');

modal_open.addEventListener('click', () => {
    modal.classList.add('show-modal');
    modal_open.classList.add('hide-btn');
})

    modal_close.addEventListener('click', () => {
    modal.classList.remove('show-modal');
    modal_open.classList.remove('hide-btn');
})

