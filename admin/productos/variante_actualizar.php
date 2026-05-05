<?php
require '../../config/db.php';

$id = (int) $_POST['id'];
$producto_id = (int) $_POST['producto_id'];

/* ===============================
   IMAGEN (SI SE SUBE)
================================ */
if (!empty($_FILES['imagen']['name'])) {
    $imagen = time() . '_' . basename($_FILES['imagen']['name']);
    move_uploaded_file(
        $_FILES['imagen']['tmp_name'],
        "../../uploads/$imagen"
    );

    $pdo->prepare("
        UPDATE producto_variantes 
        SET imagen=? 
        WHERE id=?
    ")->execute([$imagen, $id]);
}

/* ===============================
   DATOS BASE (INCLUYE TALLA Y COLOR)
================================ */
$pdo->prepare("
    UPDATE producto_variantes 
    SET 
        precio = ?,
        stock = ?,
        costo = ?,
        talla_id = ?,
        color_id = ?
    WHERE id = ?
")->execute([
    $_POST['precio'],
    $_POST['stock'],
    $_POST['costo'] !== '' ? $_POST['costo'] : null,
    $_POST['talla_id'],
    $_POST['color_id'],
    $id
]);

/* ===============================
   ATRIBUTOS (RESET)
================================ */
$pdo->prepare("
    DELETE FROM variante_atributos 
    WHERE variante_id = ?
")->execute([$id]);

if (!empty($_POST['atributos'])) {
    $stmt = $pdo->prepare("
        INSERT INTO variante_atributos 
        (variante_id, atributo_id, valor_id)
        VALUES (?,?,?)
    ");

    foreach ($_POST['atributos'] as $atributo_id => $valor_id) {
        if (!$valor_id) continue;

        $stmt->execute([
            $id,
            $atributo_id,
            $valor_id
        ]);
    }
}

/* ===============================
   REDIRECCIÓN
================================ */
header("Location: variantes.php?id=$producto_id");
exit;
