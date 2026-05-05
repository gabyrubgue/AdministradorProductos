<?php
require_once '../../config/db.php';
require '../layout/header.php';
$id = $_GET['id'];
$t = $pdo->query("SELECT * FROM tallas WHERE id=$id")->fetch();
?>
<div class="card">
<h2>✏️ Editar talla</h2>

<form action="actualizar.php" method="POST">
    <input type="hidden" name="id" value="<?= $id ?>">

    <input name="nombre" value="<?= $t['nombre'] ?>" required>

    <select name="tipo">
        <option value="anillo" <?= $t['tipo']=='anillo'?'selected':'' ?>>Anillo</option>
        <option value="pulsera" <?= $t['tipo']=='pulsera'?'selected':'' ?>>Pulsera</option>
        <option value="cadena" <?= $t['tipo']=='cadena'?'selected':'' ?>>Cadena</option>
        <option value="general" <?= $t['tipo']=='general'?'selected':'' ?>>General</option>
    </select>

    <select name="estado">
        <option value="activo" <?= $t['estado']=='activo'?'selected':'' ?>>Activo</option>
        <option value="inactivo" <?= $t['estado']=='inactivo'?'selected':'' ?>>Inactivo</option>
    </select>

    <button>Actualizar</button>
</form>
</div>
<?php require '../layout/footer.php'; ?>