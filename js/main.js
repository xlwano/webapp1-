// Zoek het hamburger icoontje op in de HTML via het id="hamburger"
const hamburger = document.getElementById('hamburger');

// Zoek het navigatiemenu op in de HTML via het id="nav"
const nav = document.getElementById('nav');

// Luister of er op het hamburger icoontje geklikt wordt
hamburger.addEventListener('click', () => {

    // Voeg de CSS klasse 'open' toe aan het menu als die er nog niet op zit
    // Als de klasse er al op zit, haal hem dan weg (toggle = aan/uit zetten)
    // De CSS klasse 'open' zorgt ervoor dat het menu zichtbaar wordt
    nav.classList.toggle('open');

});
