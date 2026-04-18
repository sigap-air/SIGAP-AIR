import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement)) return;

    const message = form.dataset.confirm;
    if (!message) return;
    if (form.dataset.confirmed === 'true') return;

    event.preventDefault();
    const isConfirmed = window.confirm(message);

    if (isConfirmed) {
        form.dataset.confirmed = 'true';
        form.submit();
    }
});
