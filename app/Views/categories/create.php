<section class="space-y-6 max-w-lg">
  <h1 class="text-2xl font-bold">Nueva categoría</h1>

  <?php if (!empty($error)): ?>
    <div class="p-3 rounded border border-rose-700 bg-rose-950/40 text-rose-200 text-sm">
      <?= htmlspecialchars((string)$error) ?>
    </div>
  <?php endif; ?>

  <form method="post" action="<?= htmlspecialchars($basePath) ?>/categories" class="space-y-4">
    <div>
      <label class="block text-sm text-slate-300 mb-1">Nombre</label>
      <input name="name" class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700"
             placeholder="Ej: Bebidas">
    </div>

    <div class="flex gap-2">
      <button class="px-3 py-2 rounded bg-indigo-600 hover:bg-indigo-500 text-sm" type="submit">Guardar</button>
      <a class="px-3 py-2 rounded bg-slate-800 hover:bg-slate-700 text-sm"
         href="<?= htmlspecialchars($basePath) ?>/categories">Cancelar</a>
    </div>
  </form>
</section>