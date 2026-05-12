<?php 
require_once 'includes/db.php';

$mensaje = "";
$tipo    = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $estudiante_id  = $_POST['estudiante_votado_id'];
    $categoria_id   = $_POST['categoria_id'];
    $nombre_votante = trim($_POST['nombre_votante']);
    $comentario     = trim($_POST['comentario']);

    if (!empty($estudiante_id) && !empty($categoria_id)) {
        $stmt = $pdo->prepare("INSERT INTO votos (estudiante_votado_id, categoria_id, nombre_votante, comentario) VALUES (?, ?, ?, ?)");
        $stmt->execute([$estudiante_id, $categoria_id, $nombre_votante, $comentario]);
        $mensaje = "✅ ¡Voto registrado correctamente! Gracias por participar.";
        $tipo    = "success";
    } else {
        $mensaje = "❌ Por favor selecciona un estudiante y una categoría.";
        $tipo    = "error";
    }
}

$estudiantes = $pdo->query("SELECT * FROM estudiantes WHERE estado = 'Activo' ORDER BY nombre ASC")->fetchAll();
$categorias  = $pdo->query("SELECT * FROM categorias WHERE estado = 'Abierta' ORDER BY nombre_categoria ASC")->fetchAll();

include 'includes/header.php'; 
?>

<div class="animate-fade-in">
  <div style="max-width:720px; margin:2rem auto 0;">

    <div class="section-header">
      <h1>🗳️ Emitir tu Voto</h1>
      <p>Selecciona una categoría y un compañero. ¡Deja un comentario divertido!</p>
    </div>

    <div class="card">

      <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $tipo; ?>"><?php echo $mensaje; ?></div>
      <?php endif; ?>

      <form action="votar.php" method="POST">
        <div class="grid" style="grid-template-columns:1fr 1fr; gap:1.25rem;">
          <div class="form-group" style="margin-bottom:0;">
            <label for="categoria_id">🏆 Categoría del Ranking</label>
            <select id="categoria_id" name="categoria_id" required>
              <option value="">Elige una categoría...</option>
              <?php foreach ($categorias as $cat): ?>
                <option value="<?php echo $cat['id']; ?>">
                  <?php echo htmlspecialchars($cat['nombre_categoria']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label for="estudiante_votado_id">👤 Compañero a Votar</label>
            <select id="estudiante_votado_id" name="estudiante_votado_id" required>
              <option value="">Elige un estudiante...</option>
              <?php foreach ($estudiantes as $est): ?>
                <option value="<?php echo $est['id']; ?>">
                  <?php echo htmlspecialchars($est['nombre']) . ($est['apodo'] ? " ({$est['apodo']})" : ""); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="divider"></div>

        <div class="form-group">
          <label for="nombre_votante">✍️ Tu Nombre <span style="color:var(--text-muted)">(opcional)</span></label>
          <input type="text" id="nombre_votante" name="nombre_votante" placeholder="O vota como Anónimo...">
        </div>

        <div class="form-group">
          <label for="comentario">💬 Comentario <span style="color:var(--text-muted)">(opcional)</span></label>
          <textarea id="comentario" name="comentario" placeholder="¿Por qué merece este ranking? ¡Sé creativo!"></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-lg" style="width:100%; margin-top:.5rem;">
          🗳️ Confirmar Voto
        </button>
      </form>
    </div>

    <p style="text-align:center; color:var(--text-muted); font-size:.85rem; margin-top:1rem;">
      Recuerda: los comentarios son para divertirse, ¡sé respetuoso con tus compañeros! 😄
    </p>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
