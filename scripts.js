document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-descr]').forEach(el => {
        el.addEventListener('mouseover', () => {
            const tooltip = document.createElement('div');
            tooltip.classList.add('tooltip');
            tooltip.innerText = el.getAttribute('data-descr');
            document.body.appendChild(tooltip);

            const rect = el.getBoundingClientRect();
            tooltip.style.left = `${rect.left}px`;
            tooltip.style.top = `${rect.bottom}px`;

            el.addEventListener('mouseout', () => tooltip.remove(), { once: true });
        });
    });
});
