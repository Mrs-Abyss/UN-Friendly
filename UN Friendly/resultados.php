<?php 
require_once 'includes/db.php';

$categoria_id = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
$categorias   = $pdo->query("SELECT * FROM categorias ORDER BY nombre_categoria ASC")->fetchAll();

if ($categoria_id == 0 && !empty($categorias)) {
    $categoria_id = $categorias[0]['id'];
}

$ranking    = [];
$comentarios = [];
$cat_nombre  = '';

if ($categoria_id > 0) {
    // Find category name
    foreach ($categorias as $c) {
        if ($c['id'] == $categoria_id) { $cat_nombre = $c['nombre_categoria']; break; }
    }

    $stmt = $pdo->prepare("
        SELECT e.nombre, e.apodo, COUNT(v.id) as total_votos
        FROM estudiantes e
        JOIN votos v ON e.id = v.estudiante_votado_id
        WHERE v.categoria_id = ?
        GROUP BY e.id
        ORDER BY total_votos DESC
    ");
    $stmt->execute([$categoria_id]);
    $ranking = $stmt->fetchAll();

    $stmt2 = $pdo->prepare("
        SELECT v.*, e.nombre as votado_nombre
        FROM votos v
        JOIN estudiantes e ON v.estudiante_votado_id = e.id
        WHERE v.categoria_id = ? AND v.comentario != ''
        ORDER BY v.fecha_voto DESC
        LIMIT 10
    ");
    $stmt2->execute([$categoria_id]);
    $comentarios = $stmt2->fetchAll();
}

$medals = ['🥇','🥈','🥉'];
$rank_classes = ['rank-1','rank-2','rank-3'];

include 'includes/header.php'; 
?>

<div class="animate-fade-in" style="margin-top:2rem;">

  <!-- TOP BAR -->
  <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:2rem;">
    <div class="section-header" style="margin-bottom:0;">
      <h1>📊 Resultados</h1>
      <?php if ($cat_nombre): ?>
        <p>Ranking: <strong style="color:var(--primary-light);"><?php echo htmlspecialchars($cat_nombre); ?></strong></p>
      <?php endif; ?>
    </div>
    <form action="resultados.php" method="GET" style="display:flex; align-items:center; gap:.75rem;">
      <label style="margin:0; white-space:nowrap;">Filtrar por</label>
      <select name="cat" onchange="this.form.submit()" style="min-width:200px;">
        <?php foreach ($categorias as $cat): ?>
          <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $categoria_id ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($cat['nombre_categoria']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </form>
  </div>

  <div class="grid" style="grid-template-columns: 1.6fr 1fr; align-items:start;">

    <!-- RANKING -->
    <div class="card">
      <h2 style="margin-bottom:1.5rem; font-size:1.1rem; text-transform:uppercase; letter-spacing:.06em; color:var(--text-muted);">Podio</h2>

      <?php if (!empty($ranking)): ?>
        <?php foreach ($ranking as $i => $row): ?>
          <?php
            $medal_icon   = $medals[$i] ?? '#' . ($i + 1);
            $extra_class  = $rank_classes[$i] ?? '';
          ?>
          <div class="ranking-item <?php echo $extra_class; ?>">
            <div class="rank-medal"><?php echo $medal_icon; ?></div>
            <div class="rank-info">
              <div class="rank-name"><?php echo htmlspecialchars($row['nombre']); ?></div>
              <?php if ($row['apodo']): ?>
              <div class="rank-alias"><?php echo htmlspecialchars($row['apodo']); ?></div>
              <?php endif; ?>
            </div>
            <span class="vote-count">⭐ <?php echo $row['total_votos']; ?> votos</span>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div style="text-align:center; padding:3rem; color:var(--text-muted);">
          <div style="font-size:3rem; margin-bottom:1rem;">🗳️</div>
          <p>Todavía no hay votos en esta categoría.<br>¡Sé el primero en votar!</p>
          <a href="votar.php" class="btn btn-primary" style="margin-top:1.5rem;">Votar Ahora</a>
        </div>
      <?php endif; ?>
    </div>

    <!-- COMMENTS -->
    <div>
      <h2 style="margin-bottom:1.25rem; font-size:1.1rem; text-transform:uppercase; letter-spacing:.06em; color:var(--text-muted);">💬 Comentarios</h2>

      <?php if (!empty($comentarios)): ?>
        <?php foreach ($comentarios as $comm): ?>
        <div class="card" style="padding:1.1rem 1.25rem; border-left:3px solid var(--primary);">
          <p style="font-style:italic; font-size:.9rem; margin-bottom:.6rem; color:var(--text);">
            "<?php echo htmlspecialchars($comm['comentario']); ?>"
          </p>
          <div style="font-size:.78rem; color:var(--text-muted);">
            <span>→ <?php echo htmlspecialchars($comm['votado_nombre']); ?></span>
            <span style="margin-left:.75rem;">
              por <strong><?php echo !empty($comm['nombre_votante']) ? htmlspecialchars($comm['nombre_votante']) : 'Anónimo'; ?></strong>
            </span>
          </div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="card" style="text-align:center; padding:2rem; color:var(--text-muted);">
          <div style="font-size:2.5rem; margin-bottom:.75rem;">🤐</div>
          <p>Nadie ha dejado comentarios aún.</p>
        </div>
      <?php endif; ?>
    </div>

  </div>
</div>

<?php include 'includes/footer.php'; ?>
