<?php
require '../../config/db.php';
$id = $_GET['id'];

$venta = $pdo->query("
    SELECT v.*, c.nombre cliente
    FROM ventas v
    LEFT JOIN clientes c ON c.id=v.cliente_id
    WHERE v.id=$id
")->fetch();

$detalles = $pdo->query("
    SELECT vd.*, p.nombre
    FROM venta_detalle vd
    JOIN producto_variantes pv ON pv.id=vd.variante_id
    JOIN productos p ON p.id=pv.producto_id
    WHERE vd.venta_id=$id
")->fetchAll();
?>

<h2>Factura #<?= $venta['id'] ?></h2>
<p>Cliente: <?= $venta['cliente'] ?? 'Consumidor final' ?></p>

<table>
<?php foreach ($detalles as $d): ?>
<tr>
<td><?= $d['nombre'] ?></td>
<td><?= $d['cantidad'] ?></td>
<td>$<?= $d['precio_venta'] ?></td>
</tr>
<?php endforeach; ?>
</table>

<h3>Total: $<?= $venta['total'] ?></h3>
<h4>Ganancia: $<?= $venta['ganancia'] ?></h4>
