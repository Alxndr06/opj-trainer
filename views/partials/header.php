<?php
// On laisse la possibilité de définir un titre de page dans le contrôleur
$pageTitle = $pageTitle ?? 'OPJ Trainer';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Application d'entraînement pour l'examen d'Officier de Police Judiciaire.">
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body>

<header class="site-header">
    <div class="site-header-inner">
        <div class="brand">
            <span class="brand-logo">⚖️</span>
            <div class="brand-text">
                <span class="brand-title"><a title="Accueil du site" href="index.php?controller=home&action=index">OPJ Trainer</a></span>
                <span class="brand-subtitle">Entraînement aux infractions & éléments constitutifs</span>
            </div>
        </div>

        <div class="site-header-meta">
            <span class="badge">Prépa O.P.J</span>
        </div>
    </div>
</header>
