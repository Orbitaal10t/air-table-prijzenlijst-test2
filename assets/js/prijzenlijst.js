document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.prijzenlijst-nav a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const header = document.querySelector('.site-header');
                const headerOffset = header ? header.offsetHeight : 170;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
            }
        });
    });
/*    // --- Automatische tekstschaal functie ---
    function scaleTextToFit(selector) {
        document.querySelectorAll(selector).forEach(el => {
            const parentWidth = el.parentElement.clientWidth;
            let fontSize = parseFloat(window.getComputedStyle(el).fontSize);

            // Reset eventuele eerdere schaal
            el.style.fontSize = '';

            // verklein font tot het past (niet kleiner dan 8px)
            while (el.scrollWidth > parentWidth && fontSize > 8) {
                fontSize -= 1;
                el.style.fontSize = fontSize + 'px';
            }
        });
    }

    // --- Pas toe op laden & bij resize ---
    function applyScaling() {
        scaleTextToFit('.table-name');
    }

    applyScaling();
    window.addEventListener('resize', applyScaling);
*/
});