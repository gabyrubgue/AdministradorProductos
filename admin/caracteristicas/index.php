<?php
require '../../config/db.php';
require '../layout/header.php';

$caracts = $pdo->query("
    SELECT 
        c.id,
        c.nombre,
        c.tipo,
        cat.nombre AS categoria
    FROM caracteristicas c
    LEFT JOIN categorias cat 
        ON c.categoria_id = cat.id
")->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="card">
<h1>🧩 Características</h1>


<a href="crear.php">
  <button type="button">➕ Nueva característica</button>
</a>

</div>
<div class="card">
<table class="admin-table">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Tipo</th>
    <th>Acciones</th>
   <th>Acciones</th>
   <th>Categoria</th>

</tr>
</div>
<?php foreach ($caracts as $c): ?>
<tr>
    <td><?= $c['id'] ?></td>
    <td><?= $c['nombre'] ?></td>
    <td><?= $c['tipo'] ?></td>
    <td><?= $c['tipo'] ?></td>
  
     
    <td>
        <a href="editar.php?id=<?= $c['id'] ?>">✏️</a>
        <a href="eliminar.php?id=<?= $c['id'] ?>" onclick="return confirm('¿Eliminar?')">🗑</a>
    </td>
      <td><?= $c['categoria'] ?></td>
</tr>
<?php endforeach; ?>
</table>

<?php require '../layout/footer.php'; ?>
