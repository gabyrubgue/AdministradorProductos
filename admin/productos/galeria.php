<?php
require '../../config/db.php';
require '../layout/header.php';

$producto_id = $_GET['id'] ?? null;
if (!$producto_id) die('Producto no válido');

/* ===============================
   PRODUCTO
================================*/
$stmt = $pdo->prepare("
    SELECT id, nombre, imagen, orden_imagen
    FROM productos
    WHERE id = ?
");
$stmt->execute([$producto_id]);
$producto = $stmt->fetch();

/* ===============================
   GALERÍA (IMÁGENES DEL PRODUCTO)
================================*/
$stmt = $pdo->prepare("
    SELECT id, imagen, orden
    FROM producto_imagenes
    WHERE producto_id = ?
");
$stmt->execute([$producto_id]);
$galeria = $stmt->fetchAll();

/* ===============================
   GALERÍA UNIFICADA
================================*/
$imagenes = [];

if ($producto['imagen']) {
    $imagenes[] = [
        'tipo' => 'principal',
        'id' => $producto_id,
        'imagen' => $producto['imagen'],
        'orden' => $producto['orden_imagen'] ?? 0
    ];
}

foreach ($galeria as $g) {
    $imagenes[] = [
        'tipo' => 'galeria',
        'id' => $g['id'],
        'imagen' => $g['imagen'],
        'orden' => $g['orden']
    ];
}

usort($imagenes, fn($a, $b) => $a['orden'] <=> $b['orden']);

/* ===============================
   MOVER ORDEN
================================*/
if (isset($_GET['mover'])) {

    $tipo = $_GET['tipo'];
    $id   = $_GET['idimg'];
    $dir  = $_GET['mover'] === 'up' ? -1 : 1;

    $actualOrden = ($tipo === 'principal')
        ? $producto['orden_imagen']
        : $pdo->query("SELECT orden FROM producto_imagenes WHERE id=$id")->fetchColumn();

    $nuevoOrden = $actualOrden + $dir;

    foreach ($imagenes as $img) {
        if ($img['orden'] == $nuevoOrden) {
            if ($img['tipo'] === 'principal') {
                $pdo->prepare("UPDATE productos SET orden_imagen=? WHERE id=?")
                    ->execute([$actualOrden, $producto_id]);
            } else {
                $pdo->prepare("UPDATE producto_imagenes SET orden=? WHERE id=?")
                    ->execute([$actualOrden, $img['id']]);
            }
        }
    }

    if ($tipo === 'principal') {
        $pdo->prepare("UPDATE productos SET orden_imagen=? WHERE id=?")
            ->execute([$nuevoOrden, $producto_id]);
    } else {
        $pdo->prepare("UPDATE producto_imagenes SET orden=? WHERE id=?")
            ->execute([$nuevoOrden, $id]);
    }

    header("Location: galeria.php?id=$producto_id");
    exit;
}

/* ===============================
   SUBIR IMAGEN (GALERÍA)
================================*/
if (isset($_POST['subir'])) {

    $nombre = time().'_'.$_FILES['imagen']['name'];
    move_uploaded_file($_FILES['imagen']['tmp_name'], "../../uploads/$nombre");

    $orden = count($imagenes) + 1;

    $pdo->prepare("
        INSERT INTO producto_imagenes (producto_id, imagen, orden)
        VALUES (?, ?, ?)
    ")->execute([$producto_id, $nombre, $orden]);

    header("Location: galeria.php?id=$producto_id");
    exit;
}

/* ===============================
   REEMPLAZAR IMAGEN
================================*/
if (isset($_POST['reemplazar'])) {

    $tipo  = $_POST['tipo'];
    $id    = $_POST['id'];
    $nuevo = time().'_'.$_FILES['imagen']['name'];

    move_uploaded_file($_FILES['imagen']['tmp_name'], "../../uploads/$nuevo");

    if ($tipo === 'principal') {
        if ($producto['imagen']) unlink("../../uploads/".$producto['imagen']);
        $pdo->prepare("UPDATE productos SET imagen=? WHERE id=?")
            ->execute([$nuevo, $producto_id]);
    } else {
        $old = $pdo->query("SELECT imagen FROM producto_imagenes WHERE id=$id")->fetchColumn();
        if ($old) unlink("../../uploads/$old");
        $pdo->prepare("UPDATE producto_imagenes SET imagen=? WHERE id=?")
            ->execute([$nuevo, $id]);
    }

    header("Location: galeria.php?id=$producto_id");
    exit;
}

/* ===============================
   ELIMINAR IMAGEN
================================*/
if (isset($_GET['eliminar'])) {

    $tipo = $_GET['tipo'];
    $id   = $_GET['idimg'];

    if ($tipo === 'principal') {
        if ($producto['imagen']) unlink("../../uploads/".$producto['imagen']);
        $pdo->prepare("UPDATE productos SET imagen=NULL")->execute();
    } else {
        $img = $pdo->query("SELECT imagen FROM producto_imagenes WHERE id=$id")->fetchColumn();
        if ($img) unlink("../../uploads/$img");
        $pdo->prepare("DELETE FROM producto_imagenes WHERE id=?")->execute([$id]);
    }

    header("Location: galeria.php?id=$producto_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Galería</title>

<style>
.galeria { display:flex; flex-wrap:wrap; gap:15px; }
.item { position:relative; width:180px; }
.item img { width:100%; height:180px; object-fit:cover; border-radius:6px; }
.principal { border:3px solid #27ae60; }

.overlay {
    position:absolute; inset:0;
    background:rgba(0,0,0,.6);
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    opacity:0;
    transition:.3s;
}
.item:hover .overlay { opacity:1; }

.overlay a, .overlay button {
    background:#fff;
    margin:4px;
    padding:5px 8px;
    font-size:12px;
    border:none;
    cursor:pointer;
    text-decoration:none;
}
</style>
</head>

<body>

<h2><?= htmlspecialchars($producto['nombre']) ?></h2>

<div class="galeria">
<?php foreach ($imagenes as $img): ?>
<div class="item <?= $img['tipo']==='principal'?'principal':'' ?>">
    <img src="../../uploads/<?= $img['imagen'] ?>">

    <div class="overlay">
        <a href="?id=<?= $producto_id ?>&mover=up&tipo=<?= $img['tipo'] ?>&idimg=<?= $img['id'] ?>">⬆</a>
        <a href="?id=<?= $producto_id ?>&mover=down&tipo=<?= $img['tipo'] ?>&idimg=<?= $img['id'] ?>">⬇</a>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="tipo" value="<?= $img['tipo'] ?>">
            <input type="hidden" name="id" value="<?= $img['id'] ?>">
            <input type="file" name="imagen" hidden required onchange="this.form.submit()">
            <button type="button" onclick="this.previousElementSibling.click()">✏️</button>
            <input type="hidden" name="reemplazar">
        </form>

        <a href="?id=<?= $producto_id ?>&eliminar=1&tipo=<?= $img['tipo'] ?>&idimg=<?= $img['id'] ?>">🗑</a>
    </div>
</div>
<?php endforeach; ?>
</div>

<hr>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="imagen" required>
    <button type="submit" name="subir">➕ Agregar imagen</button>
</form>

</body>
</html>
