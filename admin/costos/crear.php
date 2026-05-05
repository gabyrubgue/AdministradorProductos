<?php
require '../layout/header.php';
require '../../config/db.php';
?>

<div class="card">
<h2>➕ Nuevo costo adicional</h2>
<form action="guardar.php" method="POST">

    <label>Name</label>
    <input type="text" name="nombre" placeholder="Ej: Grabado" required>

    <label>Tipo de costo</label>
    <select name="tipo" required>
        <option value="unitario">Unitario (por producto)</option>
        <option value="compartido">Global (entre varios)</option>
    </select>

    <label>Precio</label>
    <input type="number" name="precio" min="0" required>


    <button>Guardar</button>
</form>
</div>

<?php require '../layout/footer.php'; ?>
