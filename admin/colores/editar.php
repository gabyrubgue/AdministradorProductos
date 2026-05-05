<?php
require_once '../../config/db.php';
require '../layout/header.php';

$id = $_GET['id'];
$c = $pdo->query("SELECT * FROM colores WHERE id=$id")->fetch();
?>
<div class="card">
<h2>✏️ Editar color</h2>

<form action="actualizar.php" method="POST">
    <input type="hidden" name="id" value="<?= $id ?>">
    <label>Nombre del color</label>
    <input name="nombre" value="<?= $c['nombre'] ?>" required>

    <label>Codigo hex</label>
    <input type="color" name="codigo_hex" value="<?= $c['codigo_hex'] ?>">

    <label>Estado</label>
    <select name="estado">
        <option value="activo" <?= $c['estado']=='activo'?'selected':'' ?>>Activo</option>
        <option value="inactivo" <?= $c['estado']=='inactivo'?'selected':'' ?>>Inactivo</option>
    </select>

    <button>Actualizar</button>
</form>
</div>
<?php require '../layout/footer.php'; ?>