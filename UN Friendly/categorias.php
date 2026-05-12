<?php 
require_once 'includes/db.php';

$mensaje = "";
$tipo    = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_cat  = trim($_POST['nombre_categoria']);
    $descripcion = trim($_POST['descripcion']);
    $creada_por  = trim($_POST['creada_por']);

    if (!empty($nombre_cat)) {
        $stmt = $pdo->prepare("INSERT INTO categorias (nombre_categoria, descripcion, creada_por) VALUES (?, ?, ?)");
        $stmt->execute([$nombre_cat, $descripcion, $creada_por]);
        $mensaje = "✅ ¡Categoría creada con éxito!";
        $tipo    = "success";
    } else {
        $mensaje = "❌ El nombre de la categoría es obligatorio.";
        $tipo    = "error";
    }
}

$categorias = $pdo->query("SELECT * FROM categorias ORDER BY fecha_creacion DESC")->fetchAll();
include 'includes/header.php'; 
?>

<div class="animate-fade-in">

  <div class="section-header" style="margin-top:2rem;">
    <h1>🏆 Categorías de Ranking</h1>
    <p>Crea rankings divertidos y permite que todos voten.</p>
  </div>

  <div class="grid" style="grid-template-columns: 380px 1fr; align-items: start;">

    <!-- FORM -->
    <div class="card" style="position:sticky; top:90px;">
      <h2 style="margin-bottom:1.5rem; font-size:1.2rem;">Nueva Categoría</h2>

      <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $tipo; ?>"><?php echo $mensaje; ?></div>
      <?php endif; ?>

      <form action="categorias.php" method="POST">
        <div class="form-group">
          <label for="nombre_categoria">Nombre del Ranking *</label>
          <input type="text" id="nombre_categoria" name="nombre_categoria" placeholder="Ej. Más fiestero 🎉" required>
        </div>
        <div class="form-group">
          <label for="descripcion">Descripción</label>
          <textarea id="descripcion" name="descripcion" placeholder="¿De qué trata este ranking?"></textarea>
        </div>
        <div class="form-group">
          <label for="creada_por">Tu Nombre</label>
          <input type="text" id="creada_por" name="creada_por" placeholder="¿Quién lo propone?">
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%; background: linear-gradient(135deg, var(--secondary), var(--primary)); margin-top:.5rem;">
          Crear Categoría
        </button>
      </form>
    </div>

    <!-- LIST -->
    <div>
      <?php if (empty($categorias)): ?>
        <div class="card" style="text-align:center; padding:3rem;">
          <div style="font-size:3rem; margin-bottom:1rem;">🤔</div>
          <p style="color:var(--text-muted);">Aún no hay categorías. ¡Sé el primero en crear una!</p>
        </div>
      <?php else: ?>
        <?php foreach ($categorias as $cat): ?>
        <div class="card" style="padding:1.5rem;">
          <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:1rem;">
            <div style="flex:1;">
              <h3 style="color:var(--primary-light); margin-bottom:.35rem; font-size:1.05rem;">
                <?php echo htmlspecialchars($cat['nombre_categoria']); ?>
              </h3>
              <?php if ($cat['descripcion']): ?>
              <p style="color:var(--text-soft); font-size:.875rem; margin-bottom:.75rem;">
                <?php echo htmlspecialchars($cat['descripcion']); ?>
              </p>
              <?php endif; ?>
              <div style="display:flex; gap:1rem; font-size:.78rem; color:var(--text-muted);">
                <span>👤 <?php echo htmlspecialchars($cat['creada_por']) ?: 'Anónimo'; ?></span>
                <span>📅 <?php echo $cat['fecha_creacion']; ?></span>
              </div>
            </div>
            <span class="badge badge-open"><?php echo $cat['estado']; ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>
</div>

<?php include 'includes/footer.php'; ?>
