<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="<?= site_url('/') ?>">← Retour au Tableau de bord</a>
    </div>
</nav>

<main class="container py-4" style="max-width: 800px;">
    <div class="mb-4">
        <h1 class="h3"><?= esc($title) ?></h1>
        <?php if (!empty($workout['notes'])): ?>
            <p class="text-muted fst-italic">"<?= esc($workout['notes']) ?>"</p>
        <?php endif; ?>
    </div>

    <?php if (empty($exercises)): ?>
        <div class="alert alert-info">Aucun exercice enregistré pour cette séance.</div>
    <?php else: ?>
        <?php foreach ($exercises as $ex): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-primary">
                        <!-- Lien cliquable vers la progression de l'exercice -->
                        <a href="<?= site_url('exercise/' . $ex['id']) ?>" class="text-decoration-none">
                            <?= esc($ex['name']) ?>
                        </a>
                    </h5>
                    <span class="badge bg-secondary"><?= esc($ex['muscle_group']) ?></span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Série</th>
                                <th>Répétitions</th>
                                <th>Charge (kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ex['sets'] as $set): ?>
                                <tr>
                                    <td class="text-muted fw-bold">#<?= esc($set['set_number']) ?></td>
                                    <td><?= esc($set['reps']) ?></td>
                                    <td class="fw-semibold"><?= esc($set['weight']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</main>
</body>
</html>