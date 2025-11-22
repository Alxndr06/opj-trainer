<?php require __DIR__ . '/../partials/header.php'; ?>

<main class="trainer-container">
    <section class="hero">
        <h1>OPJ Trainer</h1>
        <p class="subtitle">
            Génère une infraction et entraîne-toi à restituer ses éléments matériels et ses éléments moraux.
        </p>
    </section>

    <section class="controls card">
        <div class="controls-inner">
            <form method="post" class="controls-form">
                <button type="submit" name="generate" class="btn btn-primary">
                    🎲 Générer une infraction (toutes)
                </button>

                <button type="submit" name="generate_important" class="btn btn-primary">
                    ⭐ Générer une infraction prioritaire
                </button>
            </form>

            <div class="trainer-stats">
    <span class="stat-badge">
        Total infractions :
        <strong><?= (int)($stats['total'] ?? 0); ?></strong>
    </span>
                <span class="stat-badge stat-badge-accent">
        Dont prioritaires :
        <strong><?= (int)($stats['prioritaires'] ?? 0); ?></strong>
    </span>
            </div>

            <p class="visit-counter">
                Nombre de visites sur ce trainer : <strong><?= (int)($visitCount ?? 0); ?></strong>
            </p>

            <a href="index.php?controller=infraction&action=index" class="btn btn-secondary" style="margin-top: 0.5rem;">
                📚 Voir toutes les infractions
            </a>

        </div>
    </section>



    <?php if (!empty($infraction)): ?>
        <section class="card question-card">
            <h2 class="infraction-label">
                <?= htmlspecialchars($infraction['libelle'] ?? $infraction['label'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
            </h2>
            <p class="infraction-instruction">
                Saisis les différents éléments matériels et les éléments moraux attendus, un par champ.
            </p>

            <?php if (!empty($feedback)): ?>
                <div class="feedback">
                    <?php
                    $allGood = $feedback['elements_materiels']['allCorrect']
                        && $feedback['elements_moraux']['allCorrect'];
                    ?>
                    <div class="feedback-summary <?= $allGood ? 'good' : 'bad'; ?>">
                        <?php if ($allGood): ?>
                            <p>🔥 Parfait, tu as tout bon sur cette infraction.</p>
                        <?php else: ?>
                            <p>🧠 Pas grave, regarde la correction ci-dessous et recommence.</p>
                        <?php endif; ?>
                    </div>

                    <div class="feedback-grid">
                        <!-- Feedback éléments matériels -->
                        <div class="feedback-block <?= $feedback['elements_materiels']['allCorrect'] ? 'correct' : 'wrong'; ?>">
                            <h3>Éléments matériels</h3>
                            <p class="status">
                                <?= $feedback['elements_materiels']['allCorrect']
                                    ? '✅ Tous les éléments matériels sont corrects'
                                    : '❌ Au moins un élément matériel est incorrect'; ?>
                            </p>

                            <ul class="feedback-items">
                                <?php foreach ($feedback['elements_materiels']['items'] as $index => $item): ?>
                                    <li>
                                        <strong>n°<?= $index + 1; ?> :</strong>
                                        <?= $item['correct'] ? '✅' : '❌'; ?><br>
                                        <?php if (!$item['correct']): ?>
                                            <span class="expected-title">Correction :</span>
                                            <span class="expected-text">
                                            <?= nl2br(htmlspecialchars($item['expected'], ENT_QUOTES, 'UTF-8')); ?>
                                        </span>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Feedback éléments moraux -->
                        <div class="feedback-block <?= $feedback['elements_moraux']['allCorrect'] ? 'correct' : 'wrong'; ?>">
                            <h3>Éléments moraux</h3>
                            <p class="status">
                                <?= $feedback['elements_moraux']['allCorrect']
                                    ? '✅ Tous les éléments moraux sont corrects'
                                    : '❌ Au moins un élément moral est incorrect'; ?>
                            </p>

                            <ul class="feedback-items">
                                <?php foreach ($feedback['elements_moraux']['items'] as $index => $item): ?>
                                    <li>
                                        <strong>n°<?= $index + 1; ?> :</strong>
                                        <?= $item['correct'] ? '✅' : '❌'; ?><br>
                                        <?php if (!$item['correct']): ?>
                                            <span class="expected-title">Correction :</span>
                                            <span class="expected-text">
                                            <?= nl2br(htmlspecialchars($item['expected'], ENT_QUOTES, 'UTF-8')); ?>
                                        </span>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form method="post" class="answer-form">
                <input type="hidden" name="check" value="1">

                <!-- Éléments matériels -->
                <div class="field-group">
                    <label>Éléments matériels</label>

                    <?php if (!empty($infraction['elements_materiels'])): ?>
                        <?php foreach ($infraction['elements_materiels'] as $index => $expectedEm): ?>
                            <div class="field-group">
                                <label for="em-<?= $index; ?>">Élément matériel n°<?= $index + 1; ?></label>
                                <textarea
                                        id="em-<?= $index; ?>"
                                        name="elements_materiels[<?= $index; ?>]"
                                        rows="3"
                                        placeholder="Tape ici l’élément matériel n°<?= $index + 1; ?>"
                                ><?= htmlspecialchars($userInputs['elements_materiels'][$index] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucun élément matériel défini pour cette infraction.</p>
                    <?php endif; ?>
                </div>

                <!-- Éléments moraux -->
                <div class="field-group">
                    <label>Éléments moraux</label>

                    <?php if (!empty($infraction['elements_moraux'])): ?>
                        <?php foreach ($infraction['elements_moraux'] as $index => $expectedMo): ?>
                            <div class="field-group">
                                <label for="mo-<?= $index; ?>">Élément moral n°<?= $index + 1; ?></label>
                                <textarea
                                        id="mo-<?= $index; ?>"
                                        name="elements_moraux[<?= $index; ?>]"
                                        rows="3"
                                        placeholder="Tape ici l’élément moral n°<?= $index + 1; ?>"
                                ><?= htmlspecialchars($userInputs['elements_moraux'][$index] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucun élément moral défini pour cette infraction.</p>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-secondary">
                    ✅ Vérifier
                </button>
            </form>
        </section>
    <?php endif; ?>

</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>
