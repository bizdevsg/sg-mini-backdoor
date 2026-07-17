import { createIcons } from "lucide";

createIcons();

const confirmModal = document.querySelector('[data-confirm-modal]');
const autoDismissElements = document.querySelectorAll('[data-auto-dismiss]');

autoDismissElements.forEach((element) => {
    if (!(element instanceof HTMLElement)) {
        return;
    }

    const delay = Number.parseInt(element.dataset.autoDismissDelay ?? '4000', 10);

    window.setTimeout(() => {
        element.classList.add('pointer-events-none', 'translate-y-1', 'opacity-0');

        window.setTimeout(() => {
            element.remove();
        }, 300);
    }, Number.isNaN(delay) ? 4000 : delay);
});

if (confirmModal instanceof HTMLDialogElement) {
    const titleElement = confirmModal.querySelector('[data-confirm-modal-title]');
    const messageElement = confirmModal.querySelector('[data-confirm-modal-message]');
    const cancelButton = confirmModal.querySelector('[data-confirm-cancel]');
    const acceptButton = confirmModal.querySelector('[data-confirm-accept]');
    let activeForm = null;

    const closeModal = () => {
        activeForm = null;
        confirmModal.close();
    };

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-confirm-submit]');

        if (!(trigger instanceof HTMLElement)) {
            return;
        }

        const form = trigger.closest('form');

        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        activeForm = form;

        if (titleElement) {
            titleElement.textContent = trigger.dataset.confirmTitle ?? 'Lanjutkan aksi ini?';
        }

        if (messageElement) {
            messageElement.textContent =
                trigger.dataset.confirmMessage ?? 'Pastikan tindakan ini memang ingin dilakukan.';
        }

        if (acceptButton instanceof HTMLButtonElement) {
            acceptButton.textContent = trigger.dataset.confirmActionLabel ?? 'Ya, lanjutkan';
            acceptButton.className =
                trigger.dataset.confirmIntent === 'delete'
                    ? 'inline-flex items-center justify-center rounded-lg bg-red-500 px-5 py-3 text-sm font-medium text-white transition-colors hover:bg-red-400'
                    : 'inline-flex items-center justify-center rounded-lg bg-white px-5 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200';
        }

        confirmModal.showModal();
    });

    cancelButton?.addEventListener('click', closeModal);

    acceptButton?.addEventListener('click', () => {
        if (!(activeForm instanceof HTMLFormElement)) {
            closeModal();

            return;
        }

        const form = activeForm;

        closeModal();
        form.requestSubmit();
    });

    confirmModal.addEventListener('click', (event) => {
        if (event.target === confirmModal) {
            closeModal();
        }
    });

    confirmModal.addEventListener('cancel', (event) => {
        event.preventDefault();
        closeModal();
    });
}
