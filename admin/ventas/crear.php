<?php
require '../../config/db.php';

$clientes = $pdo->query("SELECT * FROM clientes")->fetchAll();
$variantes = $pdo->query("
    SELECT pv.id, p.nombre
    FROM producto_variantes pv
    JOIN productos p ON p.id = pv.producto_id
")->fetchAll();
?>

<h1>Nueva venta</h1>

<form action="guardar.php" method="POST">

<label>Cliente</label>
<select name="cliente_id">
<option value="">Consumidor final</option>
<?php foreach ($clientes as $c): ?>
<option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
<?php endforeach; ?>
</select>

<label>Variante</label>
<select name="variante_id">
<?php foreach ($variantes as $v): ?>
<option value="<?= $v['id'] ?>"><?= $v['nombre'] ?> (var #<?= $v['id'] ?>)</option>
<?php endforeach; ?>
</select>

<input name="cantidad" placeholder="Cantidad" value="1">
<input name="precio_venta" placeholder="Precio venta">

<button>Registrar venta</button>
</form>
