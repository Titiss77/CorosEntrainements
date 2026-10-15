<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-5" style="max-width: 760px">
    <div class="card border-0 shadow-sm"><div class="card-body p-4 p-md-5">
        <h1 class="h3 mb-3"><?= esc($title) ?></h1>
        <div class="alert alert-info"><?= esc($message) ?></div>
        <p>Le serveur MCP officiel est <code>https://mcp.coros.com/mcp</code>. La connexion exige OAuth 2.0 et des échanges MCP authentifiés.</p>
        <p><a href="https://support.coros.com/hc/fr/articles/53181619102996-D%C3%A9velopper-sur-COROS-MCP" target="_blank" rel="noopener">Consulter la documentation officielle COROS</a></p>
        <a href="<?= site_url('/') ?>" class="btn btn-primary">Retour au tableau de bord</a>
    </div></div>
</main>
</body>
</html>
