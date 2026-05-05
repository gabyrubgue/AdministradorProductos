<?php
require '../../config/db.php';
require '../layout/header.php';

$ventas = $pdo->query("SELECT * FROM ventas")->fetchAll();
?>
<div class="card">
<h1> Ventas</h1>

<div class="card">
    <a href="crear.php">
        <button>➕ Nueva Venta</button>
    </a>
</div>

<div class="card">
<table class="admin-table">
<tr>
    <th>ID</th>
    <th>Cliente</th>
    <th>total</th>
    <th>ganancia</th>

</tr>
</div>

  <?php foreach ($ventas as $v): ?>
<tr>
    <td><?= $v['id'] ?></td>
    <td><?= $v['cliente_id'] ?></td>
    <td><?= $v['total'] ?></td>
    <td><?= $v['ganancia'] ?></td>
    <td>
        <a href="eliminar.php?id=<?= $t['id'] ?>"
           onclick="return confirm('¿Eliminar talla?')">🗑</a>
    </td>
</tr>
<?php endforeach; ?>
</table>


<?php require '../layout/footer.php'; ?>
