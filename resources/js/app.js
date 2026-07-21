// Interacciones del sitio público de Orvet (sin dependencias externas).

document.addEventListener('DOMContentLoaded', () => {
    // Menú móvil
    const menuBtn = document.querySelector('[data-menu-toggle]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');
    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
    }

    // Slider hero
    const slider = document.querySelector('[data-slider]');
    if (slider) {
        const slides = Array.from(slider.querySelectorAll('[data-slide]'));
        const dots = Array.from(slider.querySelectorAll('[data-dot]'));
        let current = 0;

        const show = (index) => {
            current = (index + slides.length) % slides.length;
            slides.forEach((s, i) => {
                s.classList.toggle('opacity-100', i === current);
                s.classList.toggle('opacity-0', i !== current);
                s.classList.toggle('pointer-events-none', i !== current);
            });
            dots.forEach((d, i) => {
                d.classList.toggle('bg-white', i === current);
                d.classList.toggle('bg-white/40', i !== current);
            });
        };

        slider.querySelector('[data-next]')?.addEventListener('click', () => show(current + 1));
        slider.querySelector('[data-prev]')?.addEventListener('click', () => show(current - 1));
        dots.forEach((dot, i) => dot.addEventListener('click', () => show(i)));

        if (slides.length > 1) {
            let timer = setInterval(() => show(current + 1), 6000);
            slider.addEventListener('mouseenter', () => clearInterval(timer));
            slider.addEventListener('mouseleave', () => (timer = setInterval(() => show(current + 1), 6000)));
        }

        show(0);
    }
});
