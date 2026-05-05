<?php
require '../../config/db.php';
require '../layout/header.php';

$productos = $pdo->query("
    SELECT 
        p.*,
        c.nombre AS categoria,

        -- Imagen principal (producto o variante)
        COALESCE(
            p.imagen,
            (
                SELECT v.imagen
                FROM producto_variantes v
                WHERE v.producto_id = p.id
                AND v.imagen IS NOT NULL
                LIMIT 1
            )
        ) AS imagen_principal,

        -- Total variantes reales
        (
            SELECT COUNT(*)
            FROM producto_variantes v
            WHERE v.producto_id = p.id
        ) AS total_variantes

    FROM productos p
    JOIN categorias c ON c.id = p.categoria_id
    ORDER BY p.id DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Productos</title>
<link rel="stylesheet" href="../css/admin.css">


</head>
<body>

<div class="card">
    <h1>📦 Productos</h1>
    <a href="crear.php">
        <button type="button">➕ Nuevo producto</button>
    </a>
</div>

<div class="card">
<table class="admin-table">
<thead>
<tr>
    <th>Imagen</th>
    <th>Nombre</th>
    <th>Categoría</th>
    <th>Colores</th>
    <th>Tallas</th>
    <th>Material</th>
    <th>Precio </th>
    <th>Stock</th>
    <th>Acciones</th>
</tr>
</thead>
<tbody>

<?php foreach ($productos as $p): ?>
<?php
// 🔑 Regla real
$esConVariantes = $p['total_variantes'] > 0;
?>

<tr>
  

<td align="center">
<?php if ($p['imagen_principal']): ?>
    <img src="../../uploads/<?= $p['imagen_principal'] ?>" width="60px"   style="border-radius:5px">
<?php else: ?>
    <span style="opacity:.3">Sin imagen</span>
<?php endif; ?>
</td>



<td align="center"><?= htmlspecialchars($p['nombre']) ?></td>

<td align="center"><?= $p['categoria'] ?></td>






<td align="center">
<?php
$stmt = $pdo->prepare("
    SELECT DISTINCT c.nombre, c.codigo_hex
    FROM colores c
    WHERE c.id IN (
        -- Colores del producto
        SELECT pc.color_id
        FROM producto_colores pc
        WHERE pc.producto_id = ?

        UNION

        -- Colores de variantes
        SELECT pv.color_id
        FROM producto_variantes pv
        WHERE pv.producto_id = ?
        AND pv.color_id IS NOT NULL
    )
    AND c.estado = 'activo'
");

$stmt->execute([$p['id'], $p['id']]);
$colores = $stmt->fetchAll();

if ($colores):
    foreach ($colores as $c):
?>
    <div style="
        display:inline-flex;
        align-items:center;
        margin:2px 4px;
        gap:6px;
    ">
        <span style="
            width:14px;
            height:14px;
            border-radius:50%;
            background:<?= htmlspecialchars($c['codigo_hex']) ?>;
            border:1px solid #555;
            display:inline-block;
        "></span>
      
    </div><br>
<?php
    endforeach;
else:
    echo '—';
endif;
?>
</td>


<td>
<?php
$stmt = $pdo->prepare("
    SELECT DISTINCT t.nombre
    FROM tallas t
    WHERE t.id IN (
        SELECT pt.talla_id
        FROM producto_tallas pt
        WHERE pt.producto_id = ?

        UNION

        SELECT pv.talla_id
        FROM producto_variantes pv
        WHERE pv.producto_id = ?
        AND pv.talla_id IS NOT NULL
    )
");

$stmt->execute([$p['id'], $p['id']]);
$tallas = $stmt->fetchAll();

if ($tallas):
    foreach ($tallas as $t):
?>
    <span class="valores">
        <?= htmlspecialchars($t['nombre']) ?>
    </span>
<?php
    endforeach;
else:
    echo '—';
endif;
?>
</td>

<td>
<?php
$stmt = $pdo->prepare("
    SELECT m.nombre
    FROM producto_materiales pm
    JOIN materiales m ON m.id = pm.material_id
    WHERE pm.producto_id = ?
");

$stmt->execute([$p['id']]);
$materiales = $stmt->fetchAll();

if ($materiales) {
    foreach ($materiales as $m) {
        echo htmlspecialchars($m['nombre']) . "<br>";
    }
} else {
    echo '—';
}
?>
</td>


<td class="col-precios">

    <!-- Precio principal -->
    <div class="precio-base">
        $<?= number_format($p['precio'],0,',','.') ?>
    </div>

    <?php if ($esConVariantes): ?>
        <div class="precios-variantes">
            <?php
            $precios = $pdo->query("
                SELECT precio
                FROM producto_variantes
                WHERE producto_id = {$p['id']}
            ")->fetchAll();

            foreach ($precios as $pr):
            ?>
                <span class="precio-variante">
                    $<?= number_format($pr['precio'],0,',','.') ?>
                </span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</td>




<td>
<?php
$stockVariantes = $pdo->query("
    SELECT COALESCE(SUM(stock),0) AS total
    FROM producto_variantes
    WHERE producto_id = {$p['id']}
")->fetch();

$totalStock = $p['stock'] + $stockVariantes['total'];

echo "Producto: {$p['stock']}<br>";
echo "Variantes: {$stockVariantes['total']}<br>";
echo "<b>Total:</b> $totalStock";
?>
</td>


<td align="center">
    <a href="editar.php?id=<?= $p['id'] ?>">✏️</a>
    <a href="galeria.php?id=<?= $p['id'] ?>">🖼️</a>
    <a href="cambiar_estado.php?id=<?= $p['id'] ?>">
        <?= $p['estado'] === 'activo' ? '🟢' : '🔴' ?>
    </a>
    <a href="eliminar.php?id=<?= $p['id'] ?>"
       onclick="return confirm('¿Eliminar este producto y todas sus variantes?')">
       🗑️
    </a>
</td>



</tr>
<?php endforeach; ?>

</tbody>
</table>
</div>

</body>
</html>

<?php require '../layout/footer.php'; ?>
