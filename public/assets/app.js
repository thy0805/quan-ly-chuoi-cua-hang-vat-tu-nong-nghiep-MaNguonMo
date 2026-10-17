'use strict';

document.querySelectorAll('[data-toggle-password]').forEach(button => {
    button.addEventListener('click', () => {
        const input = document.getElementById(button.dataset.togglePassword);
        if (!input) return;
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        button.setAttribute('aria-label', show ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
        button.setAttribute('aria-pressed', String(show));
    });
});

const modalElement = document.getElementById('delete-modal');
let pendingForm = null;
let pendingButton = null;
document.querySelectorAll('form[data-confirm-delete]').forEach(form => {
    form.addEventListener('submit', event => {
        if (form.dataset.confirmed === 'true') return;
        event.preventDefault();
        if (!modalElement || !window.bootstrap) {
            if (window.confirm(`Xóa ${form.dataset.confirmDelete}? Thao tác này không thể hoàn tác.`)) {
                form.dataset.confirmed = 'true';
                form.requestSubmit();
            }
            return;
        }
        pendingForm = form;
        pendingButton = event.submitter;
        document.getElementById('delete-record-name').textContent = form.dataset.confirmDelete;
        bootstrap.Modal.getOrCreateInstance(modalElement).show();
    });
});
document.getElementById('delete-confirm')?.addEventListener('click', () => {
    if (!pendingForm) return;
    pendingForm.dataset.confirmed = 'true';
    pendingForm.requestSubmit();
});
modalElement?.addEventListener('hidden.bs.modal', () => {
    pendingButton?.focus();
    pendingForm = null;
    pendingButton = null;
});
