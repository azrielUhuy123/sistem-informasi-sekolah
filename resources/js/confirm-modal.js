import { Modal } from 'bootstrap';

const defaultTitle = 'Konfirmasi';
const defaultMessage = 'Apakah Anda yakin?';
const defaultConfirmText = 'Ya, Lanjutkan';
const defaultButtonClass = 'btn-danger';

const state = {
    modalElement: null,
    modal: null,
    confirmButton: null,
    titleElement: null,
    messageElement: null,
    targetForm: null,
    isProcessing: false,
};

const getConfirmText = (triggerButton) => triggerButton?.dataset.confirmText || defaultConfirmText;
const getButtonClass = (triggerButton) => triggerButton?.dataset.button || defaultButtonClass;

const setConfirmButtonState = (isLoading = false) => {
    if (!state.confirmButton) {
        return;
    }

    state.confirmButton.disabled = isLoading;
    state.confirmButton.setAttribute('aria-busy', isLoading ? 'true' : 'false');

    if (isLoading) {
        state.confirmButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengirim...';
        return;
    }

    const confirmText = state.confirmButton.dataset.confirmText || defaultConfirmText;
    state.confirmButton.innerHTML = '';
    state.confirmButton.textContent = confirmText;
};

const resetModal = () => {
    if (!state.modalElement || !state.titleElement || !state.messageElement || !state.confirmButton) {
        return;
    }

    state.titleElement.textContent = defaultTitle;
    state.messageElement.textContent = defaultMessage;
    state.confirmButton.className = `btn px-4 ${defaultButtonClass}`;
    state.confirmButton.dataset.confirmText = defaultConfirmText;
    setConfirmButtonState(false);

    if (state.targetForm) {
        state.targetForm.removeAttribute('data-confirmed');
    }

    state.targetForm = null;
    state.isProcessing = false;
};

const openConfirmModal = (triggerButton) => {
    if (!state.modalElement || !state.titleElement || !state.messageElement || !state.confirmButton) {
        return;
    }

    const form = triggerButton.closest('form');

    if (!form) {
        return;
    }

    state.targetForm = form;
    state.isProcessing = false;

    state.titleElement.textContent = triggerButton.dataset.title || defaultTitle;
    state.messageElement.textContent = triggerButton.dataset.message || defaultMessage;
    state.confirmButton.className = `btn px-4 ${getButtonClass(triggerButton)}`;
    state.confirmButton.dataset.confirmText = getConfirmText(triggerButton);
    setConfirmButtonState(false);

    state.modal.show();
};

const confirmSubmit = () => {
    if (!state.targetForm || state.isProcessing) {
        return;
    }

    const form = state.targetForm;

    if (!form.checkValidity()) {
        form.reportValidity();
        setConfirmButtonState(false);
        return;
    }

    state.isProcessing = true;
    setConfirmButtonState(true);
    form.setAttribute('data-confirmed', 'true');

    state.modal.hide();
    form.requestSubmit();
};

const bindConfirmButtons = () => {
    document.addEventListener('click', (event) => {
        const triggerButton = event.target.closest('.btn-confirm');

        if (!triggerButton) {
            return;
        }

        event.preventDefault();
        openConfirmModal(triggerButton);
    });
};

const bindFormSubmitEvents = () => {
    document.addEventListener('submit', (event) => {
        const form = event.target;

        if (form instanceof HTMLFormElement && form.getAttribute('data-confirmed') === 'true') {
            form.removeAttribute('data-confirmed');
        }
    }, true);
};

document.addEventListener('DOMContentLoaded', () => {
    state.modalElement = document.getElementById('confirmModal');

    if (!state.modalElement) {
        return;
    }

    state.modal = new Modal(state.modalElement);
    state.confirmButton = document.getElementById('confirmSubmit');
    state.titleElement = document.getElementById('confirmTitle');
    state.messageElement = document.getElementById('confirmMessage');

    if (!state.confirmButton || !state.titleElement || !state.messageElement) {
        return;
    }

    bindConfirmButtons();
    bindFormSubmitEvents();

    state.confirmButton.addEventListener('click', confirmSubmit);

    state.modalElement.addEventListener('shown.bs.modal', () => {
        state.confirmButton.focus();
    });

    state.modalElement.addEventListener('hidden.bs.modal', () => {
        resetModal();
    });
});