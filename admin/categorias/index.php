<?php
require_once '../../config/db.php';
require '../layout/header.php';

$categorias = $pdo->query("
    SELECT * FROM categorias
    ORDER BY parent_id IS NOT NULL, nombre
")->fetchAll();

function mostrarCategorias($categorias, $parent = null, $nivel = 0) {
    foreach ($categorias as $c) {
        if ($c['parent_id'] == $parent) {
            echo "<tr>";
            echo "<td>" . str_repeat('— ', $nivel) . $c['nombre'] . "</td>";
            echo "<td>{$c['estado']}</td>";
            echo "<td>
                    <a href='editar.php?id={$c['id']}'>✏️</a>
                    <a href='eliminar.php?id={$c['id']}' onclick='return confirm(\"¿Eliminar?\")'>🗑</a>
                  </td>";
            echo "</tr>";

            mostrarCategorias($categorias, $c['id'], $nivel + 1);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Categorías</title>
<link rel="stylesheet" href="../css/admin.css">
</head>
<body>



<div class="card">
<h1>📂 Categorías</h1>

<a href="crear.php">
  <button type="button">➕ Nueva categoría</button>
</a>

</div>
<div class="card">
<table class="admin-table">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Padre</th>
    <th>Acciones</th>
</tr>
</div>


<?php foreach ($categorias as $c): ?>
<tr>
    <td><?= $c['id'] ?></td>
    <td><?= $c['nombre'] ?></td>
    <td><?= $c['padre'] ?? '—' ?></td>
    <td>
        <a href="editar.php?id=<?= $c['id'] ?>">✏️</a>
        <a href="eliminar.php?id=<?= $c['id'] ?>" onclick="return confirm('¿Eliminar?')">🗑</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>

<?php require '../layout/footer.php'; ?>