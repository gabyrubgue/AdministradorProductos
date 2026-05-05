<?php
require '../../config/db.php';
require '../layout/header.php';

$costos = $pdo->query("SELECT * FROM costos ORDER BY nombre")->fetchAll();
?>

<div class="card">
    <h1>💸 Costos adicionales</h1>
    <a href="crear.php">
        <button>➕ Nuevo costo</button>
    </a>
</div>

<div class="card">
<table class="admin-table">
<thead>
<tr>
    <th>Nombre</th>
    <th>Tipo</th>
    <th>Precio</th>
    <th>Estado</th>
    <th>Acciones</th>
</tr>
</thead>

<tbody>
<?php foreach ($costos as $c): ?>
<tr>
    <td><?= htmlspecialchars($c['nombre']) ?></td>
    <td><?= ucfirst($c['tipo']) ?></td>
    <td>$ <?= number_format($c['precio'], 0, ',', '.') ?></td>

    <!-- 🔵 ESTADO DINÁMICO -->
    <td align="center">
        <a href="cambiar_estado.php?id=<?= $c['id'] ?>">
            <?= $c['estado'] === 'activo' ? '🟢' : '🔴' ?>
        </a>
    </td>

    <td align="center">
        <a href="editar.php?id=<?= $c['id'] ?>">✏️</a>
        <a href="eliminar.php?id=<?= $c['id'] ?>" 
           onclick="return confirm('¿Eliminar este costo?')">🗑️</a>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<?php require '../layout/footer.php'; ?>
