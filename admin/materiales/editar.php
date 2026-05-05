<?php
require_once '../../config/db.php';
require '../layout/header.php';

$id = $_GET['id'];
$m = $pdo->query("SELECT * FROM materiales WHERE id=$id")->fetch();
?>
<div class="card">
<h2>✏️ Editar material</h2>

<form action="actualizar.php" method="POST">
    <input type="hidden" name="id" value="<?= $id ?>">
    
    <label>Nombre del material</label>
    <input name="nombre" value="<?= $m['nombre'] ?>" required>
    
    <label>Descripción</label>
    <textarea name="descripcion"><?= $m['descripcion'] ?></textarea>

    <label>Costo base</label>
    <input type="number" step="0.01" name="costo_base"
    value="<?= $m['costo_base'] ?>">

    <label>Estado</label>
    <select name="estado">
        <option value="activo" <?= $m['estado']=='activo'?'selected':'' ?>>Activo</option>
        <option value="inactivo" <?= $m['estado']=='inactivo'?'selected':'' ?>>Inactivo</option>
    </select>

    <button>Actualizar</button>
</form>
</div>
<?php require '../layout/footer.php'; ?>