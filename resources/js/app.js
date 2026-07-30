import './bootstrap';

// Bootstrap 5 JS bundle (includes Popper for dropdowns, tooltips, modals).
import * as bootstrap from 'bootstrap';

// Expose for inline usage if ever needed.
window.bootstrap = bootstrap;

document.addEventListener('DOMContentLoaded', () => {
    // Enable tooltips project-wide.
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => {
        new bootstrap.Tooltip(el);
    });

    // Auto-dismiss flash alerts after a few seconds.
    document.querySelectorAll('.alert-auto-dismiss').forEach((el) => {
        setTimeout(() => bootstrap.Alert.getOrCreateInstance(el).close(), 4000);
    });
});
