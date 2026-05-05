<?php
require '../../config/db.php';
require '../layout/header.php';

/* =========================
   VALIDAR ID DE PRODUCTO
========================= */
if (!isset($_GET['id'])) {
    die('Producto no especificado');
}

$producto_id = (int) $_GET['id'];

/* =========================
   PRODUCTO + CATEGORIA
========================= */
$stmt = $pdo->prepare("
    SELECT id, nombre, precio, categoria_id
    FROM productos
    WHERE id = ?
");
$stmt->execute([$producto_id]);
$producto = $stmt->fetch();

if (!$producto) {
    die('Producto no encontrado');
}

/* =========================
   ATRIBUTOS
========================= */
$atributos = $pdo->query("SELECT * FROM producto_variantes")->fetchAll();


/* =========================
   TODAS LAS TALLAS (SIN FILTRAR)
========================= */
$tallas = $pdo->query("
    SELECT id, nombre, tipo
    FROM tallas
")->fetchAll();

/* =========================
   COLORES
========================= */
$colores = $pdo->query("SELECT * FROM colores")->fetchAll();
?>

<h2>Variantes de: <?= htmlspecialchars($producto['nombre']) ?></h2>
<h3>➕ Agregar variante</h3>

<form action="variante_guardar.php" method="POST" enctype="multipart/form-data">

<input type="hidden" name="producto_id" value="<?= $producto_id ?>">
<input type="hidden" name="precio_base" value="<?= $producto['precio'] ?>">

<!-- ATRIBUTOS -->
<?php foreach ($atributos as $a): ?>
    <label><?= htmlspecialchars($a['nombre']) ?></label>
    <select name="atributos[<?= $a['id'] ?>]" required>
        <?php
        $valores = $pdo->prepare("
            SELECT * FROM atributo_valores
            WHERE atributo_id = ?
        ");
        $valores->execute([$a['id']]);
        foreach ($valores as $v):
        ?>
            <option value="<?= $v['id'] ?>">
                <?= htmlspecialchars($v['valor']) ?>
            </option>
        <?php endforeach; ?>
    </select>
<?php endforeach; ?>

<hr>

<label>Precio base del producto</label>
<input type="text" value="<?= $producto['precio'] ?>" disabled>

<label>
    <input type="checkbox" id="chkPrecio">
    ¿Precio distinto para esta variante?
</label>

<div id="bloquePrecio" style="display:none;">
    <input type="number" name="precio" placeholder="Precio variante">
</div>

<hr>

<label>Stock</label>
<input type="number" name="stock" required>

<hr>

<!-- TALLAS -->
<h3>Tallas disponibles</h3>

<div id="tallasContainer">
<?php foreach ($tallas as $t): ?>
    <label class="talla-item" data-tipo="<?= $t['tipo'] ?>">
        <input type="radio" name="talla_id" value="<?= $t['id'] ?>" required>
        <?= htmlspecialchars($t['nombre']) ?>
    </label><br>
<?php endforeach; ?>
</div>

<hr>

<!-- COLORES -->
<h3>Colores</h3>
<?php foreach ($colores as $c): ?>
    <label>
        <input type="radio" name="color_id" value="<?= $c['id'] ?>" required>
        <?= htmlspecialchars($c['nombre']) ?>
    </label><br>
<?php endforeach; ?>


<hr>

<label>Imagen variante</label>
<input type="file" name="imagen">

<br><br>
<button type="submit">Agregar variante</button>
</form>

<hr>

<h2>📋 Variantes registradas</h2>

<table border="1" cellpadding="6">
<tr>
    <th>Imagen</th>
    <th>color</th>
    <th>talla</th>
    <th>Precio</th>
    <th>Stock</th>
    <th>Acciones</th>
</tr>

<?php
$stmt = $pdo->prepare("
    SELECT * FROM producto_variantes
    WHERE producto_id = ?
");
$stmt->execute([$producto_id]);
$variantes = $stmt->fetchAll();

foreach ($variantes as $v):
?>
<tr>
<td>
<?php if ($v['imagen']): ?>
    <img src="../../uploads/<?= htmlspecialchars($v['imagen']) ?>" width="50">
<?php endif; ?>
</td>

<td>
<?php
$attrs = $pdo->prepare("
    SELECT a.nombre, av.valor
    FROM variante_atributos va
    JOIN atributos a ON a.id = va.atributo_id
    JOIN atributo_valores av ON av.id = va.valor_id
    WHERE va.variante_id = ?
");
$attrs->execute([$v['id']]);

foreach ($attrs as $a) {
    echo htmlspecialchars($a['nombre']) . ': ' . htmlspecialchars($a['valor']) . '<br>';
}
?>
</td>

<td><?= $v['precio'] ?></td>
<td><?= $v['stock'] ?></td>

<td>
    <a href="variante_editar.php?id=<?= $v['id'] ?>">✏️</a>
    <a href="variante_eliminar.php?id=<?= $v['id'] ?>&p=<?= $producto_id ?>">🗑</a>
</td>
</tr>
<?php endforeach; ?>
</table>

<script>
/*
 MAPA CATEGORIA → TIPO DE TALLA
*/
const categoriaTipoMap = {
    4: 'anillo',
    5: 'pulsera',
    6: 'cadena',
};

const categoriaProducto = <?= (int) $producto['categoria_id'] ?>;
const tipoPermitido = categoriaTipoMap[categoriaProducto];

const tallasItems = document.querySelectorAll('.talla-item');

// ocultar todas
tallasItems.forEach(item => {
    item.style.display = 'none';
    item.querySelector('input').checked = false;
});

// mostrar solo las permitidas
tallasItems.forEach(item => {
    const tipo = item.dataset.tipo;
    if (tipo === tipoPermitido || tipo === 'general') {
        item.style.display = 'block';
    }
});

// precio variante
document.getElementById('chkPrecio').addEventListener('change', function () {
    document.getElementById('bloquePrecio').style.display =
        this.checked ? 'block' : 'none';
});
</script>

<?php require '../layout/footer.php'; ?>
