<section class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold">Productos</h1>
      <p class="text-slate-300 text-sm">Listado</p>
    </div>
    <a class="px-3 py-2 rounded bg-indigo-600 hover:bg-indigo-500 text-sm"
       href="<?= htmlspecialchars($basePath) ?>/products/create">Nuevo</a>
  </div>

  <?php
    // ====== DATA PARA GRAFICO (top 10 por precio) ======
    $items = [];
    foreach (($products ?? []) as $p) {
      $name  = (string)($p['name'] ?? '');
      $price = (float) str_replace(',', '.', (string)($p['price'] ?? '0'));
      $items[] = ['name' => $name, 'price' => $price];
    }
    usort($items, fn($a, $b) => $b['price'] <=> $a['price']);
    $items = array_slice($items, 0, 10);

    $labels = array_map(fn($x) => $x['name'], $items);
    $values = array_map(fn($x) => $x['price'], $items);

    $chartLabelsJson = json_encode($labels, JSON_UNESCAPED_UNICODE);
    $chartValuesJson = json_encode($values, JSON_UNESCAPED_UNICODE);
  ?>

  <!-- ====== GRAFICO ====== -->
  <div class="rounded-xl border border-slate-800 bg-slate-900/30 p-4">
    <div class="flex items-center justify-between mb-3">
      <h2 class="font-semibold">Top 10 productos por precio</h2>
      <span class="text-xs text-slate-400">(según campo price)</span>
    </div>
    <canvas id="chartTopPrice" height="120"></canvas>
  </div>

  <!-- ====== TABLA ====== -->
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

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(() => {
  const labels = <?= $chartLabelsJson ?: '[]' ?>;
  const values = <?= $chartValuesJson ?: '[]' ?>;

  const el = document.getElementById('chartTopPrice');
  if (!el) return;

  new Chart(el, {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        label: 'Precio',
        data: values,
        backgroundColor: 'rgba(16, 185, 129, 0.55)',
        borderColor: 'rgba(16, 185, 129, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true } }
    }
  });
})();
</script>