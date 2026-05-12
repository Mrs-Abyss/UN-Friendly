<?php 
require_once 'includes/db.php';

$mensaje = "";
$tipo    = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $apodo  = trim($_POST['apodo']);
    $estado = $_POST['estado'];

    if (!empty($nombre)) {
        $stmt = $pdo->prepare("INSERT INTO estudiantes (nombre, apodo, estado) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $apodo, $estado]);
        $mensaje = "✅ ¡Estudiante registrado con éxito!";
        $tipo    = "success";
    } else {
        $mensaje = "❌ El nombre es obligatorio.";
        $tipo    = "error";
    }
}

$estudiantes = $pdo->query("SELECT * FROM estudiantes ORDER BY id DESC")->fetchAll();
include 'includes/header.php'; 
?>

<div class="animate-fade-in">

  <div class="section-header" style="margin-top:2rem;">
    <h1>👨‍🎓 Registro de Estudiantes</h1>
    <p>Agrega participantes para que aparezcan en los rankings.</p>
  </div>

  <div class="grid" style="grid-template-columns: 380px 1fr; align-items: start;">

    <!-- FORM -->
    <div class="card" style="position:sticky; top:90px;">
      <h2 style="margin-bottom:1.5rem; font-size:1.2rem;">Nuevo Estudiante</h2>

      <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $tipo; ?>"><?php echo $mensaje; ?></div>
      <?php endif; ?>

      <form action="registro.php" method="POST">
        <div class="form-group">
          <label for="nombre">Nombre Completo *</label>
          <input type="text" id="nombre" name="nombre" placeholder="Ej. Juan Sebastián Pérez" required>
        </div>
        <div class="form-group">
          <label for="apodo">Apodo (Opcional)</label>
          <input type="text" id="apodo" name="apodo" placeholder="Ej. El Crack">
        </div>
        <div class="form-group">
          <label for="estado">Estado</label>
          <select id="estado" name="estado">
            <option value="Activo">✅ Activo</option>
            <option value="Inactivo">❌ Inactivo</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%; margin-top:.5rem;">
          Registrar Estudiante
        </button>
      </form>
    </div>

    <!-- LIST -->
    <div class="card">
      <h2 style="margin-bottom:1.5rem; font-size:1.2rem;">
        Participantes Registrados
        <span style="font-size:.85rem; font-weight:500; color:var(--text-muted); margin-left:.5rem;">(<?php echo count($estudiantes); ?>)</span>
      </h2>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Apodo</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($estudiantes as $i => $est): ?>
          <tr>
            <td style="color:var(--text-muted); font-size:.8rem;"><?php echo count($estudiantes) - $i; ?></td>
            <td style="font-weight:600;"><?php echo htmlspecialchars($est['nombre']); ?></td>
            <td style="color:var(--text-soft);"><?php echo htmlspecialchars($est['apodo']) ?: '—'; ?></td>
            <td>
              <span class="badge badge-<?php echo strtolower($est['estado']); ?>">
                <?php echo $est['estado']; ?>
              </span>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($estudiantes)): ?>
          <tr>
            <td colspan="4" style="text-align:center; padding:3rem; color:var(--text-muted);">
              Aún no hay estudiantes. ¡Sé el primero!
            </td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<?php include 'includes/footer.php'; ?>
