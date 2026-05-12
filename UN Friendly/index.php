<?php 
require_once 'includes/db.php';
include 'includes/header.php'; 

$totalEstudiantes = $pdo->query("SELECT COUNT(*) FROM estudiantes")->fetchColumn();
$totalCategorias  = $pdo->query("SELECT COUNT(*) FROM categorias")->fetchColumn();
$totalVotos       = $pdo->query("SELECT COUNT(*) FROM votos")->fetchColumn();
?>

<div class="animate-fade-in">

  <!-- HERO -->
  <section class="hero">
    <div class="hero-eyebrow">✨ Rankings Universitarios en Vivo</div>
    <h1>
      Vota, crea y descubre<br>
      <span class="gradient-text">quién lo tiene todo</span>
    </h1>
    <p>
      Una plataforma para que los estudiantes de la UN creen rankings divertidos,
      voten por sus compañeros y vean los resultados al instante.
    </p>
    <div class="hero-buttons">
      <a href="votar.php"     class="btn btn-primary btn-lg">🗳️ Votar Ahora</a>
      <a href="resultados.php" class="btn btn-outline btn-lg">📊 Ver Rankings</a>
    </div>
  </section>

  <!-- STATS -->
  <div class="grid" style="grid-template-columns: repeat(3,1fr); margin-bottom:3rem;">
    <div class="card card-stat">
      <div class="stat-number" style="color: var(--primary-light);"><?php echo $totalEstudiantes; ?></div>
      <div class="stat-label">👨‍🎓 Estudiantes</div>
    </div>
    <div class="card card-stat">
      <div class="stat-number" style="color: var(--secondary);"><?php echo $totalCategorias; ?></div>
      <div class="stat-label">🏆 Categorías</div>
    </div>
    <div class="card card-stat">
      <div class="stat-number" style="color: var(--accent);"><?php echo $totalVotos; ?></div>
      <div class="stat-label">🗳️ Votos Emitidos</div>
    </div>
  </div>

  <div class="divider"></div>

  <!-- HOW IT WORKS -->
  <section>
    <div class="section-header" style="text-align:center; margin-bottom:2.5rem;">
      <h1>¿Cómo funciona?</h1>
      <p>Tres pasos para unirte al ranking más épico de la universidad</p>
    </div>
    <div class="grid">
      <div class="card">
        <div style="font-size:2.5rem; margin-bottom:1rem;">1️⃣</div>
        <h3 style="margin-bottom:.5rem; color: var(--primary-light);">Regístrate</h3>
        <p style="color:var(--text-soft); font-size:.9rem;">
          Agrega tu nombre y apodo para que tus compañeros puedan votarte. Solo toma un segundo.
        </p>
      </div>
      <div class="card">
        <div style="font-size:2.5rem; margin-bottom:1rem;">2️⃣</div>
        <h3 style="margin-bottom:.5rem; color: var(--secondary);">Crea Categorías</h3>
        <p style="color:var(--text-soft); font-size:.9rem;">
          ¿Se te ocurre un ranking? "Mejor Gamer", "Más Creativo"... ¡tú propones, todos votan!
        </p>
      </div>
      <div class="card">
        <div style="font-size:2.5rem; margin-bottom:1rem;">3️⃣</div>
        <h3 style="margin-bottom:.5rem; color: var(--accent);">Vota y Comenta</h3>
        <p style="color:var(--text-soft); font-size:.9rem;">
          Elige a tu candidato favorito, deja un comentario divertido y ve subir el ranking en vivo.
        </p>
      </div>
    </div>
  </section>

</div>

<?php include 'includes/footer.php'; ?>
