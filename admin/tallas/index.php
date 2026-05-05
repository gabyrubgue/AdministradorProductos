<?php
require '../../config/db.php';
require '../layout/header.php';

$tallas = $pdo->query("SELECT * FROM tallas ORDER BY nombre")->fetchAll();
?>
<div class="card">
<h1>📏 Tallas</h1>

<div class="card">
    <a href="crear.php">
        <button>➕ Nueva talla</button>
    </a>
</div>

<div class="card">
<table class="admin-table">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Tipo</th>
    <th>Estado</th>
    <th>Acciones</th>
</tr>
</div>

  <?php foreach ($tallas as $t): ?>
<tr>
    <td><?= $t['id'] ?></td>
    <td><?= $t['nombre'] ?></td>
    <td><?= $t['tipo'] ?></td>
   
    <!-- 🔵 ESTADO DINÁMICO -->
    <td align="center">
        <a href="cambiar_estado.php?id=<?= $t['id'] ?>">
            <?= $t['estado'] === 'activo' ? '🟢' : '🔴' ?>
        </a>
    </td>

    <td>
        <a href="editar.php?id=<?= $t['id'] ?>">✏️</a>
        <a href="eliminar.php?id=<?= $t['id'] ?>"
           onclick="return confirm('¿Eliminar talla?')">🗑</a>
    </td>
</tr>
<?php endforeach; ?>
</table>


<?php require '../layout/footer.php'; ?>
