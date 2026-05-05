<?php
require '../../config/db.php';
require '../layout/header.php';

$colores = $pdo->query("SELECT * FROM colores ORDER BY nombre")->fetchAll();
?>
<div class="card">
<h1>🎨 Colores</h1>

    <a href="crear.php"> <button>➕ Nuevo color</button>
    </a>
</div>

<div class="card">
<table class="admin-table">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Color</th>
    <th>Estado</th>
    <th>Acciones</th>
</tr>
</div>

<?php foreach ($colores as $c): ?>
<tr>
    <td><?= $c['id'] ?></td>
    <td><?= $c['nombre'] ?></td>
    <td>
        <span style="display:inline-block;width:20px;height:20px;
        background:<?= $c['codigo_hex'] ?>"></span>
        <?= $c['codigo_hex'] ?>
    </td>
        <!-- 🔵 ESTADO DINÁMICO -->
    <td align="center">
        <a href="cambiar_estado.php?id=<?= $c['id'] ?>">
            <?= $c['estado'] === 'activo' ? '🟢' : '🔴' ?>
        </a>
    </td>
    <td>
        <a href="editar.php?id=<?= $c['id'] ?>">✏️</a>
        <a href="eliminar.php?id=<?= $c['id'] ?>"
           onclick="return confirm('¿Eliminar color?')">🗑</a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<?php require '../layout/footer.php'; ?>
