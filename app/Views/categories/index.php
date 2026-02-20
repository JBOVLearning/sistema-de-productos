<section class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold">Categorías</h1>
      <p class="text-slate-300 text-sm">Listado</p>
    </div>
    <a class="px-3 py-2 rounded bg-indigo-600 hover:bg-indigo-500 text-sm"
       href="<?= htmlspecialchars($basePath) ?>/categories/create">Nueva</a>
  </div>

  <?php
    // ====== DATA PARA GRAFICO (conteo de productos por categoría) ======
    // Espera que el controller pase $products (lista completa) además de $categories.
    $catLabels = [];
    $catCounts = [];

    $catNameById = [];
    foreach (($categories ?? []) as $c) {
      $id = (int)($c['id'] ?? 0);
      $name = (string)($c['name'] ?? '');
      $catNameById[$id] = $name;
      $catLabels[] = $name;
      $catCounts[$id] = 0;
    }

    foreach (($products ?? []) as $p) {
      $cid = (int)($p['category_id'] ?? 0);
      if (!isset($catCounts[$cid])) {
        // categoría no existente (data sucia) => crea bucket
        $catCounts[$cid] = 0;
        $catLabels[] = $catNameById[$cid] ?? ('#' . $cid);
      }
      $catCounts[$cid]++;
    }

    // Alinea counts al orden de labels originales (por id en categories)
    $values = [];
    foreach (($categories ?? []) as $c) {
      $id = (int)($c['id'] ?? 0);
      $values[] = (int)($catCounts[$id] ?? 0);
    }

    // Si hay buckets extra por data sucia, no los incluimos (mantener simple)
    $chartLabelsJson = json_encode($catLabels, JSON_UNESCAPED_UNICODE);
    $chartValuesJson = json_encode($values, JSON_UNESCAPED_UNICODE);
  ?>

  <!-- ====== GRAFICO ====== -->
  <div class="rounded-xl border border-slate-800 bg-slate-900/30 p-4">
    <div class="flex items-center justify-between mb-3">
      <h2 class="font-semibold">Productos por categoría</h2>
      <span class="text-xs text-slate-400">(conteo)</span>
    </div>
    <canvas id="chartCatProducts" height="120"></canvas>
  </div>

  <!-- ====== TABLA ====== -->
  <div class="rounded-xl border border-slate-800 overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-slate-900/60 text-slate-300">
        <tr>
          <th class="text-left p-3">id</th>
          <th class="text-left p-3">nombre</th>
          <th class="text-left p-3 w-48">acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-800">
        <?php foreach (($categories ?? []) as $c): ?>
          <tr class="bg-slate-950/40">
            <td class="p-3"><?= htmlspecialchars((string)$c['id']) ?></td>
            <td class="p-3"><?= htmlspecialchars((string)$c['name']) ?></td>
            <td class="p-3">
              <div class="flex gap-2">
                <a class="px-2 py-1 rounded bg-slate-800 hover:bg-slate-700"
                   href="<?= htmlspecialchars($basePath) ?>/categories/edit?id=<?= (int)$c['id'] ?>">Editar</a>

                <form method="post" action="<?= htmlspecialchars($basePath) ?>/categories/delete"
                      onsubmit="return confirm('¿Eliminar categoría?');">
                  <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                  <button class="px-2 py-1 rounded bg-rose-700 hover:bg-rose-600" type="submit">Eliminar</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>

        <?php if (empty($categories)): ?>
          <tr><td class="p-3 text-slate-400" colspan="3">Sin datos</td></tr>
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

  const el = document.getElementById('chartCatProducts');
  if (!el) return;

  new Chart(el, {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        label: 'Productos',
        data: values,
        backgroundColor: 'rgba(99, 102, 241, 0.55)',
        borderColor: 'rgba(99, 102, 241, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
  });
})();
</script>