<?php require __DIR__ . '/../partials/header.php'; ?>

<main class="trainer-container">
    <section class="hero">
        <h1>Catalogue des infractions</h1>
        <p class="subtitle">
            Liste des infractions avec accès rapide à leurs éléments matériels et moraux.
        </p>
    </section>

    <section class="card inf-list-card">
        <div class="inf-list-header">
            <h2 class="inf-list-title">Toutes les infractions</h2>
            <a href="index.php" class="btn btn-secondary inf-back-link">
                ⬅ Retour au trainer
            </a>
        </div>

        <?php if (!empty($infractions)): ?>
            <ul class="infraction-list">
                <?php foreach ($infractions as $infraction): ?>
                    <li class="infraction-item" data-infraction-id="<?= (int)$infraction['id']; ?>">
                        <div class="infraction-item-header">
                            <div class="infraction-item-main">
                                <h3 class="infraction-item-title">
                                    <?= htmlspecialchars($infraction['libelle'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                </h3>

                                <?php if (!empty($infraction['is_important'])): ?>
                                    <span class="infraction-item-badge">
                                        ⭐ Prioritaire
                                    </span>
                                <?php endif; ?>
                            </div>

                            <button type="button" class="btn btn-ghost infraction-toggle" data-toggle-details>
                                Voir les éléments
                            </button>
                        </div>

                        <div class="infraction-item-details" hidden>
                            <div class="infraction-columns">
                                <div class="infraction-column">
                                    <h4>Éléments matériels</h4>
                                    <?php if (!empty($infraction['elements_materiels'])): ?>
                                        <ul class="infraction-elements">
                                            <?php foreach ($infraction['elements_materiels'] as $index => $em): ?>
                                                <li>
                                                    <span class="infraction-element-index">
                                                        n°<?= $index + 1; ?> :
                                                    </span>
                                                    <span class="infraction-element-text">
                                                        <?= nl2br(htmlspecialchars($em, ENT_QUOTES, 'UTF-8')); ?>
                                                    </span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p class="infraction-empty">Aucun élément matériel défini.</p>
                                    <?php endif; ?>
                                </div>

                                <div class="infraction-column">
                                    <h4>Éléments moraux</h4>
                                    <?php if (!empty($infraction['elements_moraux'])): ?>
                                        <ul class="infraction-elements">
                                            <?php foreach ($infraction['elements_moraux'] as $index => $mo): ?>
                                                <li>
                                                    <span class="infraction-element-index">
                                                        n°<?= $index + 1; ?> :
                                                    </span>
                                                    <span class="infraction-element-text">
                                                        <?= nl2br(htmlspecialchars($mo, ENT_QUOTES, 'UTF-8')); ?>
                                                    </span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p class="infraction-empty">Aucun élément moral défini.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="infraction-empty">
                Aucune infraction n'est enregistrée pour le moment.
            </p>
        <?php endif; ?>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const buttons = document.querySelectorAll('[data-toggle-details]');

        buttons.forEach((btn) => {
            btn.addEventListener('click', () => {
                const item = btn.closest('.infraction-item');
                const details = item.querySelector('.infraction-item-details');
                const isHidden = details.hasAttribute('hidden');

                // Option : fermer les autres
                document.querySelectorAll('.infraction-item-details').forEach((block) => {
                    if (block !== details) {
                        block.setAttribute('hidden', 'hidden');
                    }
                });
                document.querySelectorAll('.infraction-toggle').forEach((otherBtn) => {
                    if (otherBtn !== btn) {
                        otherBtn.textContent = 'Voir les éléments';
                    }
                });

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
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
