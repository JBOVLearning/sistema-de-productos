<section class="space-y-6 max-w-lg">
  <h1 class="text-2xl font-bold">Editar categoría</h1>

  <form method="post" action="<?= htmlspecialchars($basePath) ?>/categories/update" class="space-y-4">
    <input type="hidden" name="id" value="<?= (int)$category['id'] ?>">

    <div>
      <label class="block text-sm text-slate-300 mb-1">Nombre</label>
      <input name="name" class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700"
             value="<?= htmlspecialchars((string)$category['name']) ?>">
    </div>

    <div class="flex gap-2">
      <button class="px-3 py-2 rounded bg-indigo-600 hover:bg-indigo-500 text-sm" type="submit">Actualizar</button>
      <a class="px-3 py-2 rounded bg-slate-800 hover:bg-slate-700 text-sm"
         href="<?= htmlspecialchars($basePath) ?>/categories">Cancelar</a>
    </div>
  </form>
</section>