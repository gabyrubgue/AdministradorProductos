<?php
require '../../config/db.php';
require '../layout/header.php';

$id = $_GET['id'];

$c = $pdo->prepare("SELECT * FROM caracteristicas WHERE id=?");
$c->execute([$id]);
$caracteristica = $c->fetch();

$categorias = $pdo->query("
    SELECT id, nombre 
    FROM categorias 
    WHERE estado='activo'
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
<h2>✏️ Editar característica</h2>

<form action="actualizar.php" method="POST">
<input type="hidden" name="id" value="<?= $id ?>">

<label>Nombre</label>
<input name="nombre" value="<?= $caracteristica['nombre'] ?>" required>

<label>Tipo</label>
<select name="tipo">
    <option value="texto" <?= $caracteristica['tipo']=='texto'?'selected':'' ?>>Texto</option>
    <option value="numero" <?= $caracteristica['tipo']=='numero'?'selected':'' ?>>Número</option>
    <option value="booleano" <?= $caracteristica['tipo']=='booleano'?'selected':'' ?>>Sí / No</option>
</select>

<label>Aplica a la categoría</label>
<select name="categoria_id">
    <option value="">Todas las categorías</option>
    <?php foreach ($categorias as $cat): ?>
        <option value="<?= $cat['id'] ?>"
            <?= $caracteristica['categoria_id']==$cat['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['nombre']) ?>
        </option>
    <?php endforeach; ?>
</select>

<br><br>
<button type="submit">Actualizar</button>
</form>
</div>

<?php require '../layout/footer.php'; ?>
