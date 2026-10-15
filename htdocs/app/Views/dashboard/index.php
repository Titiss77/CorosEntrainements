<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #f4f6f9; }
        .stat-card { border: 0; border-radius: 16px; box-shadow: 0 3px 16px rgba(20,30,50,.06); }
        .stat-value { font-size: clamp(1.5rem, 3vw, 2.1rem); font-weight: 700; }
        .muted { color: #6c757d; }
    </style>
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="<?= site_url('dashboard') ?>">COROS · Entraînements</a>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-light btn-sm" href="<?= site_url('activites') ?>">Activités</a>
            <a class="btn btn-warning btn-sm" href="<?= site_url('coros/connect') ?>">Connecter COROS</a>
        </div>
    </div>
</nav>
<main class="container py-4">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-warning"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif ?>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Tableau de bord</h1>
            <p class="muted mb-0">Vue d’ensemble de tes activités enregistrées.</p>
        </div>
        <form method="post" action="<?= site_url('coros/sync') ?>">
            <?= csrf_field() ?>
            <button class="btn btn-primary" type="submit">Synchroniser COROS</button>
        </form>
    </div>

    <?php
        $totalDistanceKm = ((float) ($stats['total_distance_meters'] ?? 0)) / 1000;
        $totalSeconds = (int) ($stats['total_duration_seconds'] ?? 0);
        $durationText = sprintf('%dh %02d', intdiv($totalSeconds, 3600), intdiv($totalSeconds % 3600, 60));
    ?>
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3"><div class="card stat-card h-100"><div class="card-body"><div class="muted">Activités</div><div class="stat-value"><?= number_format((int) ($stats['total_activities'] ?? 0), 0, ',', ' ') ?></div></div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="card stat-card h-100"><div class="card-body"><div class="muted">Distance totale</div><div class="stat-value"><?= number_format($totalDistanceKm, 1, ',', ' ') ?> <small class="fs-6">km</small></div></div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="card stat-card h-100"><div class="card-body"><div class="muted">Temps cumulé</div><div class="stat-value"><?= esc($durationText) ?> <small class="fs-6">h</small></div></div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="card stat-card h-100"><div class="card-body"><div class="muted">FC moyenne</div><div class="stat-value"><?= (int) round((float) ($stats['average_heart_rate'] ?? 0)) ?> <small class="fs-6">bpm</small></div></div></div></div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-7">
            <div class="card stat-card h-100"><div class="card-body">
                <h2 class="h5 mb-3">Distance mensuelle</h2>
                <?php if (empty($monthlyDistance)): ?>
                    <p class="muted">Aucune activité en base pour le moment. Le graphique se remplira après la première synchronisation.</p>
                <?php else: ?>
                    <canvas id="distanceChart" height="120"></canvas>
                <?php endif ?>
            </div></div>
        </div>
        <div class="col-12 col-xl-5">
            <div class="card stat-card h-100"><div class="card-body">
                <h2 class="h5 mb-3">Activités récentes</h2>
                <?php if (empty($activities)): ?>
                    <p class="muted">Aucune activité enregistrée. Une fois la connexion COROS configurée, les séances apparaîtront ici.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($activities as $activity): ?>
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between gap-3">
                                    <div>
                                        <div class="fw-semibold"><?= esc($activity['name'] ?: ($activity['sport'] ?: 'Activité')) ?></div>
                                        <div class="small muted"><?= esc($activity['started_at'] ?: 'Date inconnue') ?> · <?= esc($activity['sport'] ?: 'Sport non précisé') ?></div>
                                    </div>
                                    <div class="text-end text-nowrap fw-semibold">
                                        <?= number_format(((float) ($activity['distance_meters'] ?? 0)) / 1000, 2, ',', ' ') ?> km
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                    <a class="btn btn-outline-secondary btn-sm mt-3" href="<?= site_url('activites') ?>">Voir toutes les activités</a>
                <?php endif ?>
            </div></div>
        </div>
    </div>
    <p class="small muted mt-4">Les statistiques reflètent uniquement les données présentes dans ta base de données.</p>
</main>
<?php if (!empty($monthlyDistance)): ?>
<script>
const monthlyLabels = <?= json_encode(array_column($monthlyDistance, 'month_key'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
const monthlyValues = <?= json_encode(array_map(static fn($row) => round(((float) $row['distance_meters']) / 1000, 2), $monthlyDistance)) ?>;
new Chart(document.getElementById('distanceChart'), {
    type: 'bar',
    data: { labels: monthlyLabels, datasets: [{ label: 'Distance (km)', data: monthlyValues, borderWidth: 1 }] },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>
<?php endif ?>
</body>
</html>
