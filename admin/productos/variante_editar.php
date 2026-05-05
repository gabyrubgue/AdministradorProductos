<?php
require '../../config/db.php';
require '../layout/header.php';

if (!isset($_GET['id'])) {
    die('Variante no especificada');
}

$id = (int) $_GET['id'];

/* =========================
   VARIANTE
========================= */
$stmt = $pdo->prepare("
    SELECT *
    FROM producto_variantes
    WHERE id = ?
");
$stmt->execute([$id]);
$variante = $stmt->fetch();

if (!$variante) {
    die('Variante no encontrada');
}

/* =========================
   ATRIBUTOS
========================= */
$atributos = $pdo->query("SELECT * FROM atributos")->fetchAll();

/* valores actuales */
$valoresActuales = $pdo->prepare("
    SELECT atributo_id, valor_id
    FROM variante_atributos
    WHERE variante_id = ?
");
$valoresActuales->execute([$id]);
$valoresActuales = $valoresActuales->fetchAll(PDO::FETCH_KEY_PAIR);

/* =========================
   TALLAS Y COLORES
========================= */
$tallas = $pdo->query("SELECT * FROM tallas")->fetchAll();
$colores = $pdo->query("SELECT * FROM colores")->fetchAll();
?>

<h2>✏️ Editar Variante</h2>

<form action="variante_actualizar.php" method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $variante['id'] ?>">
<input type="hidden" name="producto_id" value="<?= $variante['producto_id'] ?>">

<!-- ATRIBUTOS -->
<h4>Atributos</h4>
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
    $selected = ($valoresActuales[$a['id']] ?? null) == $v['id']
        ? 'selected' : '';
?>
<option value="<?= $v['id'] ?>" <?= $selected ?>>
    <?= htmlspecialchars($v['valor']) ?>
</option>
<?php endforeach; ?>
</select><br>
<?php endforeach; ?>

<hr>

<!-- TALLA -->
<h4>Talla</h4>
<?php foreach ($tallas as $t): ?>
<label>
<input type="radio" name="talla_id"
       value="<?= $t['id'] ?>"
       <?= $t['id'] == $variante['talla_id'] ? 'checked' : '' ?>
       required>
<?= htmlspecialchars($t['nombre']) ?>
</label><br>
<?php endforeach; ?>

<hr>

<!-- COLOR -->
<h4>Color</h4>
<?php foreach ($colores as $c): ?>
<label>
<input type="radio" name="color_id"
       value="<?= $c['id'] ?>"
       <?= $c['id'] == $variante['color_id'] ? 'checked' : '' ?>
       required>
<?= htmlspecialchars($c['nombre']) ?>
</label><br>
<?php endforeach; ?>

<hr>

<label>Precio</label>
<input type="number" name="precio" value="<?= $variante['precio'] ?>" required>

<label>Stock</label>
<input type="number" name="stock" value="<?= $variante['stock'] ?>" required>


<label>Imagen</label><br>
<?php if ($variante['imagen']): ?>
<img src="../../uploads/<?= htmlspecialchars($variante['imagen']) ?>" width="80"><br>
<?php endif; ?>
<input type="file" name="imagen">

<br><br>
<button>💾 Actualizar variante</button>
</form>

<?php require '../layout/footer.php'; ?>
