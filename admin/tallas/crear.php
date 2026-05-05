<?php
require '../layout/header.php';
?>
<div class="card">
<h2>➕ Nueva talla</h2>

<form action="guardar.php" method="POST">

    <label>Talla</label>
    <input type="text" name="nombre" placeholder="Ej: 6 ½" required>
    
    <select name="tipo">
        <option value="anillo">Anillo</option>
        <option value="pulsera">Pulsera</option>
        <option value="cadena">Cadena</option>
        <option value="general">General</option>
    </select>

    <button>Guardar</button>
</form>
</div>

<?php require '../layout/footer.php'; ?>
