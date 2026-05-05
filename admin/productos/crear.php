<?php
require '../../config/db.php';
require '../layout/header.php';

$categorias = $pdo->query("SELECT * FROM categorias WHERE estado='activo'")->fetchAll();
$materiales = $pdo->query("SELECT * FROM materiales WHERE estado='activo'")->fetchAll();
$colores = $pdo->query("SELECT * FROM colores WHERE estado='activo'")->fetchAll();
$caracteristicas = $pdo->query("SELECT * FROM caracteristicas")->fetchAll();
$tallas = $pdo->query("SELECT * FROM tallas")->fetchAll();


?>

<h2>➕ Nuevo Producto</h2>

<form action="guardar.php" method="POST" enctype="multipart/form-data">

<label>Nombre</label>
<input type="text" name="nombre" required>

<label>Categoría</label>
<select name="categoria_id" id="categoria" required>
<option value="">Seleccione</option>
<?php foreach ($categorias as $c): ?>
<option value="<?= $c['id'] ?>"  ?>
    <?= $c['nombre'] ?>
</option>
<?php endforeach; ?>
</select>



<label>Descripción</label>
<textarea name="descripcion"></textarea>
<label>Precio </label>
<input type="number" step="0.01" name="precio" required>

<label>Stock</label>
<input type="number" name="stock" id="stock" min="1" required>

<h3>Materiales</h3>
<?php foreach ($materiales as $m): ?>
<label>
<input type="checkbox" name="materiales[]" value="<?= $m['id'] ?>">
<?= $m['nombre'] ?>
</label><br>
<?php endforeach; ?>

<h3>Colores</h3>
<?php foreach ($colores as $c): ?>
<label>
<input type="checkbox" name="colores[]" value="<?= $c['id'] ?>">
<?= $c['nombre'] ?>
</label><br>
<?php endforeach; ?>


<h3>Características</h3>

<?php foreach ($caracteristicas as $c): ?>
    <div class="caracteristica-item"
         data-categoria="<?= $c['categoria_id'] ?? 'general' ?>"
         style="display:none">

        <label><?= $c['nombre'] ?></label>

        <?php if ($c['tipo'] === 'texto'): ?>
            <input type="text"
                   name="caracteristicas[<?= $c['id'] ?>]">
        <?php elseif ($c['tipo'] === 'numero'): ?>
            <input type="number"
                   name="caracteristicas[<?= $c['id'] ?>]">
        <?php else: ?>
            <input type="checkbox"
                   name="caracteristicas[<?= $c['id'] ?>]"
                   value="1">
        <?php endif; ?>

        <br>
    </div>
<?php endforeach; ?>



<div id="bloqueVariantes" style="display:none;">
<label>¿Este producto tiene variantes?</label><br>
<label><input type="radio" name="tiene_variantes" value="1"> Sí</label>
<label><input type="radio" name="tiene_variantes" value="0" checked> No</label>
</div>
<!-- TALLAS DEL PRODUCTO -->
<div id="tallasProducto">
    <h3>Tallas del producto</h3>
    <?php foreach ($tallas as $t): ?>
        <label class="talla-item" data-tipo="<?= $t['tipo'] ?>">
            <input type="radio" name="tallas_producto" value="<?= $t['id'] ?>">
            <?= $t['nombre'] ?>
        </label><br>
    <?php endforeach; ?>
</div>




<label>Imagen del producto</label>
<input type="file" name="imagen">

<br><br>
<button>Guardar</button>
</form>

<script>
const categoria = document.getElementById('categoria');
const tallasItems = document.querySelectorAll('.talla-item');
const stock = document.getElementById('stock');
const bloqueVariantes = document.getElementById('bloqueVariantes');
const tallasProducto = document.getElementById('tallasProducto');
const radios = document.querySelectorAll('input[name="tiene_variantes"]');

/*
 MAPA CATEGORÍA → TIPO DE TALLA
 Ajusta los IDs según tu tabla categorias
*/
const categoriaTipoMap = {
    4: 'anillo',
    5: 'pulsera',
    6: 'cadena',
};

// 🔹 ocultar todas las tallas al inicio
tallasItems.forEach(item => item.style.display = 'none');

// 🔹 filtrar tallas según categoría
function filtrarTallas() {
    const categoriaId = categoria.value;
    const tipoPermitido = categoriaTipoMap[categoriaId];

    tallasItems.forEach(item => {
        const tipoTalla = item.dataset.tipo;

        if (!tipoPermitido) {
            item.style.display = 'none';
            item.querySelector('input').checked = false;
            return;
        }

        if (tipoTalla === tipoPermitido || tipoTalla === 'general') {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
            item.querySelector('input').checked = false;
        }
    });
}

// 🔹 cambio de categoría
categoria.addEventListener('change', filtrarTallas);

// 🔹 mostrar bloque variantes si stock > 1
stock.addEventListener('input', () => {
    bloqueVariantes.style.display = stock.value > 1 ? 'block' : 'none';
});

// 🔹 variantes sí / no
radios.forEach(radio => {
    radio.addEventListener('change', () => {
        if (radio.value === '1') {
            tallasProducto.style.display = 'none';
        } else {
            
        }
    });
});
</script>

<script>
const categoriaSelect = document.getElementById('categoria');
const caracteristicas = document.querySelectorAll('.caracteristica-item');

// 🔹 Mostrar solo las características de la categoría seleccionada
function filtrarCaracteristicas() {
    const categoriaId = categoriaSelect.value;

    caracteristicas.forEach(item => {
        const categoriaCaract = item.dataset.categoria;

        // Si no hay categoría seleccionada
        if (!categoriaId) {
            item.style.display = 'none';
            limpiar(item);
            return;
        }

        // Mostrar si coincide o es general
        if (
            categoriaCaract === categoriaId ||
            categoriaCaract === 'general'
        ) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
            limpiar(item);
        }
    });
}

// 🔹 Limpiar inputs ocultos (muy importante)
function limpiar(item) {
    item.querySelectorAll('input').forEach(input => {
        if (input.type === 'checkbox') {
            input.checked = false;
        } else {
            input.value = '';
        }
    });
}

// 🔹 Evento cambio de categoría
categoriaSelect.addEventListener('change', filtrarCaracteristicas);

// 🔹 Al cargar: ocultar todo
caracteristicas.forEach(item => item.style.display = 'none');
</script>


<?php require '../layout/footer.php'; ?>
