<?php
require '../../config/db.php';
require '../layout/header.php';

$materiales = $pdo->query("SELECT * FROM materiales ORDER BY nombre")->fetchAll();
?>
<div class="card">
<h1>🧱 Materiales</h1>
    <a href="crear.php">
        <button>➕ Nuevo material</button>
    </a>
</div>

<div class="card">
    <table class="admin-table">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Costo base</th>
    <th>Estado</th>
    <th>Acciones</th>
</tr>
</div>

<?php foreach ($materiales as $m): ?>
<tr>
    <td><?= $m['id'] ?></td>
    <td><?= $m['nombre'] ?></td>
    <td>$<?= number_format($m['costo_base'],2) ?></td>
    
        <!-- 🔵 ESTADO DINÁMICO -->
    <td align="center">
        <a href="cambiar_estado.php?id=<?= $m['id'] ?>">
            <?= $m['estado'] === 'activo' ? '🟢' : '🔴' ?>
        </a>
    </td>

    <td>
        <a href="editar.php?id=<?= $m['id'] ?>">✏️</a>
        <a href="eliminar.php?id=<?= $m['id'] ?>"
           onclick="return confirm('¿Eliminar material?')">🗑</a>
    </td>
</tr>
<?php endforeach; ?>
</table>


<?php require '../layout/footer.php'; ?>
