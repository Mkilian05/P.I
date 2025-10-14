// Fade-in ao rolar
const faders = document.querySelectorAll('.fade-in');

const appearOptions = {
    threshold: 0.2,
    rootMargin: "0px 0px -50px 0px"
};

const appearOnScroll = new IntersectionObserver(function(entries, appearOnScroll) {
    entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('visible');
        appearOnScroll.unobserve(entry.target);
    });
}, appearOptions);

faders.forEach(fader => {
    appearOnScroll.observe(fader);
});

// Hover nos botões da Hero
const heroBtns = document.querySelectorAll('.hero-btn');

heroBtns.forEach(btn => {
    btn.addEventListener('mouseenter', () => {
        btn.style.transform = 'scale(1.05)';
    });
    btn.addEventListener('mouseleave', () => {
        btn.style.transform = 'scale(1)';
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const heroElements = document.querySelectorAll(".hero-section h1, .hero-section p, .hero-section .btn");

    function revealHero() {
        heroElements.forEach((el, index) => {
            setTimeout(() => {
                el.classList.add("visible");
            }, index * 300); // delay entre cada elemento
        });
    }

    // Executa a animação logo que a página carregar
    revealHero();
});

