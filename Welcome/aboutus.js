const scroll_indicator = document.getElementById("scroll-indicator");

window.addEventListener("scroll", function () {
  const maxScrollHeight = document.documentElement.scrollHeight - window.innerHeight;
  const currentScrollHeight = (window.pageYOffset / maxScrollHeight) * 100;
  scroll_indicator.style.width = `${currentScrollHeight}%`;
});

/* ScrollReveal Animations */
window.sr = ScrollReveal({
  reset: true,
  distance: "200px",
  duration: 2000,
});

// Welcome section
sr.reveal(".midscreen-text h1", {
  origin: "bottom"
});
sr.reveal(".midscreen-text p", {
  origin: "bottom",
  delay: 200
});

// About Us section
sr.reveal("#aboutus .image-container", {
  origin: "left"
});
sr.reveal("#aboutus .text-container", {
  origin: "right"
});

// The Team section
sr.reveal("#theteam .image-container", {
  origin: "right"
});
sr.reveal("#theteam .text-container", {
  origin: "left"
});

// Services section
sr.reveal("#services .image-container", {
  origin: "left"
});
sr.reveal("#services .text-container", {
  origin: "right"
});

//intro animation

let intro= document.querySelector('.intro');
let logo= document.querySelector('.teknoy-header');
let logoSpan= document.querySelectorAll('.tekno');

window.addEventListener('DOMContentLoaded', ()=>{

    setTimeout(()=>{
        logoSpan.forEach((span, idx)=>{
            setTimeout(()=>{
                span.classList.add('active');
            }, (idx+1) *400)
        });
        
        setTimeout(()=>{
            logoSpan.forEach((span, idx)=>{

                setTimeout(()=>{
                    span.classList.remove('active');
                    span.classList.add('fade');
                }, (idx+1)*50)
            })
        },2000)
        setTimeout(()=>{
            intro.style.top = '-100vh';
        },2300)
    })
})


// Hamburger menu functionality
const hamburgerMenu = document.querySelector('.hamburger-menu');
const mobileMenu = document.querySelector('.mobile-menu');
const overlay = document.createElement('div');
overlay.classList.add('overlay');
document.body.appendChild(overlay);

function toggleMobileMenu() {
    hamburgerMenu.classList.toggle('active');
    mobileMenu.classList.toggle('active');
    overlay.classList.toggle('active');
    document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
}

hamburgerMenu.addEventListener('click', toggleMobileMenu);

// Close mobile menu when clicking outside
overlay.addEventListener('click', toggleMobileMenu);

// Close mobile menu when a link is clicked
const mobileMenuLinks = mobileMenu.querySelectorAll('a');
mobileMenuLinks.forEach(link => {
    link.addEventListener('click', toggleMobileMenu);
});


