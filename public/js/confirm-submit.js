(() => {
    const confirmModal = document.querySelector('[data-confirm-modal]');

    if (!(confirmModal instanceof HTMLElement)) {
        return;
    }

    const titleElement = confirmModal.querySelector('[data-confirm-modal-title]');
    const messageElement = confirmModal.querySelector('[data-confirm-modal-message]');
    const cancelButton = confirmModal.querySelector('[data-confirm-cancel]');
    const acceptButton = confirmModal.querySelector('[data-confirm-accept]');
    const backdrop = confirmModal.querySelector('[data-confirm-backdrop]');
    const pageBody = document.body;
    let activeForm = null;
    let lastFocusedElement = null;

    const baseAcceptButtonClass =
        'inline-flex items-center justify-center rounded-xl px-5 py-3 text-sm font-medium transition-colors';

    const setAcceptButtonIntent = (intent) => {
        if (!(acceptButton instanceof HTMLButtonElement)) {
            return;
        }

        acceptButton.className =
            intent === 'delete'
                ? `${baseAcceptButtonClass} bg-red-500 text-white hover:bg-red-400`
                : `${baseAcceptButtonClass} bg-white text-obsidian hover:bg-slate-200`;
    };

    const openModal = () => {
        confirmModal.classList.remove('hidden');
        confirmModal.classList.add('flex');
        confirmModal.setAttribute('aria-hidden', 'false');
        pageBody.classList.add('overflow-hidden');
        window.setTimeout(() => acceptButton?.focus(), 0);
    };

    const closeModal = () => {
        confirmModal.classList.add('hidden');
        confirmModal.classList.remove('flex');
        confirmModal.setAttribute('aria-hidden', 'true');
        pageBody.classList.remove('overflow-hidden');
        activeForm = null;

        if (lastFocusedElement instanceof HTMLElement) {
            lastFocusedElement.focus();
        }
    };

    document.addEventListener('click', (event) => {
        const target = event.target;

        if (!(target instanceof Element)) {
            return;
        }

        const trigger = target.closest('[data-confirm-submit]');

        if (!(trigger instanceof HTMLElement)) {
            return;
        }

        const form = trigger.closest('form');

        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        event.preventDefault();
        activeForm = form;
        lastFocusedElement = trigger;

        if (titleElement instanceof HTMLElement) {
            titleElement.textContent = trigger.dataset.confirmTitle ?? 'Lanjutkan aksi ini?';
        }

        if (messageElement instanceof HTMLElement) {
            messageElement.textContent =
                trigger.dataset.confirmMessage ?? 'Pastikan tindakan ini memang ingin dilakukan.';
        }

        if (acceptButton instanceof HTMLButtonElement) {
            acceptButton.textContent = trigger.dataset.confirmActionLabel ?? 'Ya, lanjutkan';
        }

        setAcceptButtonIntent(trigger.dataset.confirmIntent);
        openModal();
    });

    cancelButton?.addEventListener('click', closeModal);
    backdrop?.addEventListener('click', closeModal);

    acceptButton?.addEventListener('click', () => {
        if (!(activeForm instanceof HTMLFormElement)) {
            closeModal();
            return;
        }

        const form = activeForm;

        closeModal();

        if (typeof form.requestSubmit === 'function') {
            form.requestSubmit();
            return;
        }

        form.submit();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape' || confirmModal.classList.contains('hidden')) {
            return;
        }

        event.preventDefault();
        closeModal();
    });
})();
