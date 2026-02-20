<section class="space-y-6 max-w-lg">
  <h1 class="text-2xl font-bold">Editar producto</h1>

  <form method="post" action="<?= htmlspecialchars($basePath) ?>/products/update" class="space-y-4">
    <input type="hidden" name="id" value="<?= (int)$product['id'] ?>">

    <div>
      <label class="block text-sm text-slate-300 mb-1">Categoría</label>
      <select name="category_id" class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700">
        <?php foreach (($categories ?? []) as $c): ?>
          <option value="<?= (int)$c['id'] ?>"
            <?= ((int)$product['category_id'] === (int)$c['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars((string)$c['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>
      <label class="block text-sm text-slate-300 mb-1">Nombre</label>
      <input name="name" class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700"
             value="<?= htmlspecialchars((string)$product['name']) ?>">
    </div>

    <div>
      <label class="block text-sm text-slate-300 mb-1">Precio</label>
      <input name="price" class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700"
             value="<?= htmlspecialchars((string)$product['price']) ?>">
    </div>

    <div class="flex gap-2">
      <button class="px-3 py-2 rounded bg-indigo-600 hover:bg-indigo-500 text-sm" type="submit">Actualizar</button>
      <a class="px-3 py-2 rounded bg-slate-800 hover:bg-slate-700 text-sm"
         href="<?= htmlspecialchars($basePath) ?>/products">Cancelar</a>
    </div>
  </form>
</section>