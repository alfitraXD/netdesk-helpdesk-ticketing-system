document.addEventListener('DOMContentLoaded', function () {
    const deleteForms = document.querySelectorAll('[data-confirm-delete]');

    deleteForms.forEach((form) => {
        form.addEventListener('submit', function (event) {
            const message = form.getAttribute('data-confirm-delete') || 'Are you sure you want to delete this data?';
            if (!confirm(message)) {
                event.preventDefault();
            }
        });
    });

    const statusSelect = document.querySelector('[data-status-preview]');
    const statusPreview = document.querySelector('[data-status-preview-target]');

    if (statusSelect && statusPreview) {
        statusSelect.addEventListener('change', function () {
            statusPreview.textContent = statusSelect.value;
        });
    }
});
