<?php
require '../../config/db.php';
require '../layout/header.php';

// Obtener categorías activas
$categorias = $pdo->query("
    SELECT id, nombre 
    FROM categorias 
    WHERE estado='activo'
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
<h2>➕ Nueva característica</h2>

<form action="guardar.php" method="POST">

<label>Nombre</label>
<input name="nombre" placeholder="Ej: Peso, Largo, Diámetro" required>

<label>Tipo de dato</label>
<select name="tipo" required>
    <option value="texto">Texto</option>
    <option value="numero">Número</option>
    <option value="booleano">Sí / No</option>
</select>

<label>Aplica a la categoría</label>
<select name="categoria_id">
    <option value="">Todas las categorías</option>
    <?php foreach ($categorias as $c): ?>
        <option value="<?= $c['id'] ?>">
            <?= htmlspecialchars($c['nombre']) ?>
        </option>
    <?php endforeach; ?>
</select>

<br><br>
<button type="submit">Guardar</button>
</form>
</div>

<?php require '../layout/footer.php'; ?>
