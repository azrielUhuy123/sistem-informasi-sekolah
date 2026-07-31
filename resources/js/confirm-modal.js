import { Modal } from 'bootstrap';

let targetForm = null;

document.addEventListener('DOMContentLoaded', () => {

    const modalElement = document.getElementById('confirmModal');

    if (!modalElement) return;

    const modal = new Modal(modalElement);

    document.addEventListener('click', function (e) {

        const btn = e.target.closest('.btn-confirm');

        if (!btn) return;

        e.preventDefault();

        targetForm = btn.closest('form');

        document.getElementById('confirmTitle').textContent =
            btn.dataset.title ?? 'Konfirmasi';

        document.getElementById('confirmMessage').textContent =
            btn.dataset.message ?? 'Apakah Anda yakin?';

        const submit = document.getElementById('confirmSubmit');

        submit.className = 'btn px-4 ' + (btn.dataset.button ?? 'btn-danger');

        modal.show();

    });

    document.getElementById('confirmSubmit').addEventListener('click', () => {

        if (targetForm) {
            targetForm.submit();
        }

    });

});