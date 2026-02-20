<section class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold">Productos</h1>
      <p class="text-slate-300 text-sm">Listado</p>
    </div>
    <a class="px-3 py-2 rounded bg-indigo-600 hover:bg-indigo-500 text-sm"
       href="<?= htmlspecialchars($basePath) ?>/products/create">Nuevo</a>
  </div>

  <div class="rounded-xl border border-slate-800 overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-slate-900/60 text-slate-300">
        <tr>
          <th class="text-left p-3">id</th>
          <th class="text-left p-3">producto</th>
          <th class="text-left p-3">categoría</th>
          <th class="text-left p-3">precio</th>
          <th class="text-left p-3 w-48">acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-800">
        <?php foreach (($products ?? []) as $p): ?>
          <tr class="bg-slate-950/40">
            <td class="p-3"><?= (int)$p['id'] ?></td>
            <td class="p-3"><?= htmlspecialchars((string)$p['name']) ?></td>
            <td class="p-3"><?= htmlspecialchars((string)($p['category_name'] ?? ('#' . (int)$p['category_id']))) ?></td>
            <td class="p-3"><?= htmlspecialchars((string)$p['price']) ?></td>
            <td class="p-3">
              <div class="flex gap-2">
                <a class="px-2 py-1 rounded bg-slate-800 hover:bg-slate-700"
                   href="<?= htmlspecialchars($basePath) ?>/products/edit?id=<?= (int)$p['id'] ?>">Editar</a>

                <form method="post" action="<?= htmlspecialchars($basePath) ?>/products/delete"
                      onsubmit="return confirm('¿Eliminar producto?');">
                  <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                  <button class="px-2 py-1 rounded bg-rose-700 hover:bg-rose-600" type="submit">Eliminar</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>

        <?php if (empty($products)): ?>
          <tr><td class="p-3 text-slate-400" colspan="5">Sin datos</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>