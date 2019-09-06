//==
//== Main body script
//== ======================================= ==//

const siteHeader = document.querySelector('.site-header');
siteHeader.classList.toggle('is-sticky', pageYOffset > 0);

window.addEventListener('scroll', () => {
    siteHeader.classList.toggle('is-sticky', pageYOffset > 0);
});
