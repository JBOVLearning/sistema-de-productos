<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Sistema de Productos</title>
  <link rel="stylesheet" href="<?= htmlspecialchars($basePath) ?>/public/assets/app.css">
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
  <header class="border-b border-slate-800">
    <div class="mx-auto max-w-5xl px-4 py-4 flex items-center justify-between">
      <a href="<?= htmlspecialchars($basePath) ?>/" class="font-semibold">Sistema</a>
      <nav class="flex gap-4 text-sm text-slate-300">
        <a class="hover:text-white" href="<?= htmlspecialchars($basePath) ?>/categories">Categorías</a>
        <a class="hover:text-white" href="<?= htmlspecialchars($basePath) ?>/products">Productos</a>
      </nav>
    </div>
  </header>

  <main class="mx-auto max-w-5xl px-4 py-8">
    <?= $content ?>
  </main>
</body>
</html>