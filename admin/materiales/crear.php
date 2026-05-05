<?php
require '../layout/header.php';
?>

<div class="card">
<h2>➕ Nuevo material</h2>

<form action="guardar.php" method="POST">

    <label>Nombre del material</label>
    <input type="text" name="nombre" placeholder="Ej: Oro 18k" required>

    <label>Descripción</label>
    <input type="text" name="descripcion" placeholder="Opcional">

    <label>Costo base</label>
    <input type="number" step="0.01" name="costo_base" required>

    <button>Guardar</button>
</form>
</div>

<?php require '../layout/footer.php'; ?>
