<?php
require_once '../../config/db.php';
require '../layout/header.php';
$id = $_GET['id'];

$id = $_GET['id'];
$categoria = $pdo->query("SELECT * FROM categorias WHERE id=$id")->fetch();
$cats = $pdo->query("SELECT * FROM categorias WHERE id != $id")->fetchAll();
?>

<div class="card">
<h2>✏️ Editar categoría</h2>

<form action="actualizar.php" method="POST">
<input type="hidden" name="id" value="<?= $id ?>">

<input name="nombre" value="<?= $categoria['nombre'] ?>" required><br><br>

<select name="parent_id">
    <option value="">— Ninguna —</option>
   <?php foreach ($cats as $c): ?>
        <option value="<?= $c['id'] ?>"
            <?= $categoria['parent_id']==$c['id']?'selected':'' ?>>
            <?= $c['nombre'] ?>
        </option>
    <?php endforeach; ?>
</select><br><br>
</select>

<select name="estado">
    <option value="activo" <?= $categoria['estado']=='activo'?'selected':'' ?>>Activo</option>
    <option value="inactivo" <?= $categoria['estado']=='inactivo'?'selected':'' ?>>Inactivo</option>
</select>

<button>Actualizar</button>
</div>
</form>
<?php require '../layout/footer.php'; ?>
