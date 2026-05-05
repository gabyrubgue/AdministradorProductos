<?php
require '../../config/db.php';
require '../layout/header.php';

$id = $_GET['id'];

/* ===============================
   PRODUCTO
================================ */
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id=?");
$stmt->execute([$id]);
$producto = $stmt->fetch();
$materialesProducto = $pdo->query("
    SELECT material_id FROM producto_materiales
    WHERE producto_id = $id
")->fetchAll(PDO::FETCH_COLUMN);

$coloresProducto = $pdo->query("
    SELECT color_id FROM producto_colores
    WHERE producto_id = $id
")->fetchAll(PDO::FETCH_COLUMN);

/* ===============================
   CATÁLOGOS
================================ */
$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll();
$materiales = $pdo->query("SELECT * FROM materiales WHERE estado='activo'")->fetchAll();
$colores = $pdo->query("SELECT * FROM colores WHERE estado='activo'")->fetchAll();
$caracteristicas = $pdo->query("SELECT * FROM caracteristicas")->fetchAll();
$tallas = $pdo->query("SELECT * FROM tallas")->fetchAll();

/* ===============================
   TALLAS DEL PRODUCTO (SIN VARIANTES)
================================ */
$tallasProducto = $pdo->query("
    SELECT talla_id FROM producto_tallas
    WHERE producto_id = $id
")->fetchAll(PDO::FETCH_COLUMN);
?>

<h2>✏️ Editar Producto</h2>

<form action="actualizar.php" method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $producto['id'] ?>">

<!-- ===============================
     NOMBRE
================================ -->
<label>Nombre</label>
<input type="text" name="nombre"
       value="<?= htmlspecialchars($producto['nombre']) ?>" required>

<!-- ===============================
     CATEGORÍA
================================ -->
<label>Categoría</label>
<select name="categoria_id" required>
<?php foreach ($categorias as $c): ?>
<option value="<?= $c['id'] ?>"
    <?= $c['id']==$producto['categoria_id']?'selected':'' ?>>
    <?= $c['nombre'] ?>
</option>
<?php endforeach; ?>
</select>

<!-- ===============================
     DESCRIPCIÓN
================================ -->
<label>Descripción</label>
<textarea name="descripcion"><?= htmlspecialchars($producto['descripcion']) ?></textarea>

<!-- ===============================
     STOCK
================================ -->
<label>Stock total</label>
<input type="number" name="stock" id="stock"
       value="<?= $producto['stock'] ?>" min="0" required>

<!-- ===============================
     VARIANTES (solo si stock > 1)
================================ -->
<div id="bloqueVariantes" style="display:none;">
    <label>¿Este producto tiene variantes?</label>
    <select name="tiene_variantes" id="tiene_variantes">
        <option value="0" <?= !$producto['tiene_variantes']?'selected':'' ?>>NO</option>
        <option value="1" <?= $producto['tiene_variantes']?'selected':'' ?>>SÍ</option>
    </select>
</div>

<!-- ===============================
     TALLAS (solo si NO variantes)
================================ -->
<div id="bloqueTallas">
    <label>Tallas del producto</label><br>
    <?php foreach ($tallas as $t): ?>
        <label>
            <input type="radio" name="tallas_producto"
                   value="<?= $t['id'] ?>"
                   <?= in_array($t['id'], $tallasProducto)?'checked':'' ?>>
            <?= $t['nombre'] ?>
        </label><br>
    <?php endforeach; ?>
</div>

<!-- ===============================
     CARACTERÍSTICAS
================================ -->
<h4>Características</h4>
<?php foreach ($caracteristicas as $car): ?>
<label><?= $car['nombre'] ?></label>
<input type="text" name="caracteristicas[<?= $car['id'] ?>]">
<?php endforeach; ?>

<!-- ===============================
     MATERIALES
================================ -->
<h4>Materiales</h4>
<?php foreach ($materiales as $m): ?>
<label>
    <input type="checkbox"
           name="materiales[]"
           value="<?= $m['id'] ?>"
           <?= in_array($m['id'], $materialesProducto) ? 'checked' : '' ?>>
    <?= $m['nombre'] ?>
</label><br>
<?php endforeach; ?>


<!-- ===============================
     COLOR BASE
================================ -->
<h4>Colores</h4>
<?php foreach ($colores as $c): ?>
<label>
    <input type="checkbox"
           name="colores[]"
           value="<?= $c['id'] ?>"
           <?= in_array($c['id'], $coloresProducto) ? 'checked' : '' ?>>
    <?= $c['nombre'] ?>
</label><br>
<?php endforeach; ?>


<!-- ===============================
     IMAGEN
================================ -->
<label>Imagen</label>
<?php if ($producto['imagen']): ?>
<br>
<img src="../../uploads/<?= $producto['imagen'] ?>" width="120">
<?php endif; ?>
<input type="file" name="imagen">

<br><br>
<button>💾 Actualizar producto</button>
</form>

<hr>

<?php if ($producto['tiene_variantes']): ?>
    <button class="btn-variantes"
        onclick="window.location.href='variantes.php?id=<?= $producto['id'] ?>'">
        🧩 Gestionar variantes
    </button>
<?php endif; ?>


<!-- ===============================
     JS
================================ -->
<script>
const stockInput = document.getElementById('stock');
const bloqueVariantes = document.getElementById('bloqueVariantes');
const bloqueTallas = document.getElementById('bloqueTallas');
const tieneVariantes = document.getElementById('tiene_variantes');

function validarStock() {
    if (parseInt(stockInput.value) > 1) {
        bloqueVariantes.style.display = 'block';
    } else {
        bloqueVariantes.style.display = 'none';
        tieneVariantes.value = 0;
        bloqueTallas.style.display = 'block';
    }
}

stockInput.addEventListener('input', validarStock);

if (tieneVariantes) {
    tieneVariantes.addEventListener('change', () => {
        bloqueTallas.style.display =
            tieneVariantes.value == 1 ? 'none' : 'block';
    });
}

validarStock();
</script>

<?php require '../layout/footer.php'; ?>
