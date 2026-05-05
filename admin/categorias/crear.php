<?php
require '../../config/db.php';
require '../layout/header.php';


$cats = $pdo->query("SELECT * FROM categorias WHERE estado='activo'")->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Nueva categoría</title>
</head>
<body>
<div class="card">
<h2>➕ Nueva categoría</h2>

<form action="guardar.php" method="POST">

<label>Nombre</label><br>
<input type="text" name="nombre" required><br><br>

<label>Categoría padre</label><br>
<select name="parent_id">
    <option value="">— Ninguna —</option>
    <?php foreach ($cats as $c): ?>
            <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
        <?php endforeach; ?>
</select><br><br>

<button>Guardar</button>
</form>
</div>
</body>
</html>

<?php require '../layout/footer.php'; ?>
