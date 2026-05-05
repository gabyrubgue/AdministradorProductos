<?php
require '../layout/header.php';
?>
<div class="card">
<h1>➕ Nuevo color</h1>

<form action="guardar.php" method="POST">

    <label>Nombre del color</label>
    <input type="text" name="nombre" placeholder="Ej: Verde esmeralda" required>

    <label>Código HEX (opcional)</label>
    <input type="color" name="codigo_hex">

    <button>Guardar</button>
</form>
</div>

<?php require '../layout/footer.php'; ?>
