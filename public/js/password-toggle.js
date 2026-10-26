(() => {
    const initializePasswordToggles = () => {
        document.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
            if (!(toggle instanceof HTMLButtonElement) || toggle.dataset.passwordToggleInitialized === 'true') {
                return;
            }

            const targetId = toggle.dataset.passwordTarget;
            const passwordInput = targetId ? document.getElementById(targetId) : null;
            const passwordIcon = toggle.querySelector('[data-password-icon]');

            if (!(passwordInput instanceof HTMLInputElement) || !(passwordIcon instanceof HTMLElement)) {
                return;
            }

            toggle.dataset.passwordToggleInitialized = 'true';

            toggle.addEventListener('click', () => {
                const shouldShowPassword = passwordInput.type === 'password';
                const label = shouldShowPassword ? 'Sembunyikan password' : 'Tampilkan password';

                passwordInput.type = shouldShowPassword ? 'text' : 'password';
                toggle.setAttribute('aria-label', label);
                toggle.setAttribute('aria-pressed', String(shouldShowPassword));
                toggle.setAttribute('title', label);
                passwordIcon.classList.toggle('fa-eye', !shouldShowPassword);
                passwordIcon.classList.toggle('fa-eye-slash', shouldShowPassword);
            });
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializePasswordToggles, { once: true });
    } else {
        initializePasswordToggles();
    }
})();
