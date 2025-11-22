document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('[data-toggle-details]');

    buttons.forEach((btn) => {
        btn.addEventListener('click', () => {
            const item = btn.closest('.infraction-item');
            const details = item.querySelector('.infraction-item-details');
            const isHidden = details.hasAttribute('hidden');

            // Fermer les autres blocs de détails
            document.querySelectorAll('.infraction-item-details').forEach((block) => {
                if (block !== details) {
                    block.setAttribute('hidden', 'hidden');
                }
            });

            // Remettre le texte par défaut sur les autres boutons
            document.querySelectorAll('.infraction-toggle').forEach((otherBtn) => {
                if (otherBtn !== btn) {
                    otherBtn.textContent = 'Voir les éléments';
                }
            });

            // Toggle de l'item courant
            if (isHidden) {
                details.removeAttribute('hidden');
                btn.textContent = 'Masquer les éléments';
            } else {
                details.setAttribute('hidden', 'hidden');
                btn.textContent = 'Voir les éléments';
            }
        });
    });
});
