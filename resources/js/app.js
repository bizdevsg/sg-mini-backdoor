import { createIcons } from "lucide";

createIcons();

const initializeAutoDismiss = () => {
    const autoDismissElements = document.querySelectorAll('[data-auto-dismiss]');

    autoDismissElements.forEach((element) => {
        if (!(element instanceof HTMLElement) || element.dataset.autoDismissInitialized === 'true') {
            return;
        }

        element.dataset.autoDismissInitialized = 'true';

        const delay = Number.parseInt(element.dataset.autoDismissDelay ?? '4000', 10);
        const duration = Number.isNaN(delay) ? 4000 : delay;
        const closeButton = element.querySelector('[data-auto-dismiss-close]');
        const progressBar = element.querySelector('[data-auto-dismiss-progress]');
        let dismissed = false;

        if (progressBar instanceof HTMLElement) {
            progressBar.style.transition = `transform ${duration}ms linear`;

            window.requestAnimationFrame(() => {
                progressBar.style.transform = 'scaleX(0)';
            });
        }

        const dismiss = () => {
            if (dismissed) {
                return;
            }

            dismissed = true;
            element.classList.add('pointer-events-none', '-translate-y-2', 'opacity-0');

            window.setTimeout(() => {
                element.remove();
            }, 320);
        };

        if (closeButton instanceof HTMLElement) {
            closeButton.addEventListener('click', dismiss);
        }

        window.setTimeout(dismiss, duration);
    });
};

const initializeCollapsibleDetails = () => {
    const collapsibleElements = document.querySelectorAll('details[data-collapsible]');

    collapsibleElements.forEach((element) => {
        if (!(element instanceof HTMLDetailsElement) || element.dataset.collapsibleInitialized === 'true') {
            return;
        }

        const trigger = element.querySelector('[data-collapsible-trigger]');
        const content = element.querySelector('[data-collapsible-content]');

        if (!(trigger instanceof HTMLElement) || !(content instanceof HTMLElement)) {
            return;
        }

        element.dataset.collapsibleInitialized = 'true';

        const syncState = (isOpen) => {
            element.dataset.state = isOpen ? 'open' : 'closed';
        };

        if (element.open) {
            syncState(true);
            content.style.height = 'auto';
            content.style.opacity = '1';
        } else {
            syncState(false);
            content.style.height = '0px';
            content.style.opacity = '0';
        }

        trigger.addEventListener('click', (event) => {
            event.preventDefault();

            if (element.dataset.collapsibleAnimating === 'true') {
                return;
            }

            const shouldOpen = element.dataset.state !== 'open';
            element.dataset.collapsibleAnimating = 'true';
            syncState(shouldOpen);

            if (shouldOpen) {
                element.open = true;
                content.style.height = '0px';
                content.style.opacity = '0';

                window.requestAnimationFrame(() => {
                    content.style.height = `${content.scrollHeight}px`;
                    content.style.opacity = '1';
                });

                return;
            }

            content.style.height = `${content.scrollHeight}px`;
            content.style.opacity = '1';

            window.requestAnimationFrame(() => {
                content.style.height = '0px';
                content.style.opacity = '0';
            });
        });

        content.addEventListener('transitionend', (event) => {
            if (event.target !== content || event.propertyName !== 'height') {
                return;
            }

            const isOpen = element.dataset.state === 'open';

            if (isOpen) {
                content.style.height = 'auto';
                content.style.opacity = '1';
            } else {
                element.open = false;
                content.style.height = '0px';
                content.style.opacity = '0';
            }

            element.dataset.collapsibleAnimating = 'false';
        });
    });
};

initializeAutoDismiss();
initializeCollapsibleDetails();
