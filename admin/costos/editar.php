<?php
require_once '../../config/db.php';
require '../layout/header.php';

$id = $_GET['id'];
$c = $pdo->query("SELECT * FROM costos WHERE id=$id")->fetch();
?>
<div class="card">
<h2>✏️ Editar costo</h2>

<form action="actualizar.php" method="POST">
<input type="hidden" name="id" value="<?= $id ?>">

<label>Nombre</label>
<input name="nombre" value="<?= $c['nombre'] ?>" required>

<label>Tipo</label>
<select name="tipo">
    <option value="unitario" <?= $c['tipo']=='unitario'?'selected':'' ?>>Unitario</option>
    <option value="global" <?= $c['tipo']=='global'?'selected':'' ?>>Global</option>
</select>

 <label>Precio </label>
  <input  name="precio" min="0"  value="<?= $c['precio'] ?>" required>



<select name="estado">
    <option value="activo" <?= $c['estado']=='activo'?'selected':'' ?>>Activo</option>
    <option value="inactivo" <?= $c['estado']=='inactivo'?'selected':'' ?>>Inactivo</option>
</select>

<button>Actualizar</button>
</form>
</div>
<?php require '../layout/footer.php'; ?>