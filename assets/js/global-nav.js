document.addEventListener('DOMContentLoaded', function () {
    const links = document.querySelectorAll('.prijzenlijst-global-nav a');
    const header = document.querySelector('.site-header'); // jouw sticky header
    const headerOffset = header ? header.offsetHeight : 170;

    // Smooth scroll bij klik
    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Optioneel: actieve link highlighten tijdens scroll
    const sections = Array.from(links).map(link => {
        const section = document.querySelector(link.getAttribute('href'));
        return { link, section };
    });

    window.addEventListener('scroll', () => {
        const scrollPos = window.scrollY + headerOffset + 10;

        sections.forEach(({ link, section }) => {
            if (!section) return;
            const top = section.offsetTop;
            const bottom = top + section.offsetHeight;

            if (scrollPos >= top && scrollPos < bottom) {
                links.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
            }
        });
    });
});