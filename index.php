<?php
require 'config.php';
$pdo = db();

// Helper to fetch lists
$vendedores = $pdo->query('SELECT codigo, nombre FROM vendedores ORDER BY codigo')->fetchAll();
$inventario = $pdo->query('SELECT * FROM inventario ORDER BY producto')->fetchAll();
$actas = $pdo->query('SELECT * FROM actas ORDER BY id')->fetchAll();

function numeroActaFor($i){ return sprintf('ACTA-%03d', $i+1); }
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Gestión Documental - Comercializadora COILE</title>
<style>
body{font-family:Arial, sans-serif; margin:20px; color:#111}
header{display:flex; align-items:center; gap:12px}
header img{height:64px}
.card{border:1px solid #ddd; padding:12px; border-radius:8px; margin-top:12px}
label{display:block;margin-top:8px}
input,select,textarea{width:100%;padding:6px;margin-top:4px;border:1px solid #ccc;border-radius:6px}
.row{display:flex; gap:8px}
.row > *{flex:1}
.table{width:100%;border-collapse:collapse;margin-top:8px}
.table th, .table td{border:1px solid #eee;padding:6px;text-align:left}
.actions button{margin-right:6px}
</style>
</head>
<body>

<header>
  <img src="assets/coile.webp" alt="Coile">
  <div>
    <h1>ACTA DE ENTREGA</h1>
    <div>COMERCIALIZADORA COILE S.A · AGENCIA SANTA ROSA</div>
  </div>
</header>

<div class="card">
  <h3>Nuevo / Editar Acta</h3>
  <form action="actions.php" method="post">
    <input type="hidden" name="action" value="save_acta">
    <input type="hidden" name="id" value="">
    <div class="row">
      <div>
        <label>Fecha</label>
        <input type="date" name="fecha" value="<?= date('Y-m-d') ?>">
      </div>
      <div>
        <label>Vendedor</label>
        <select name="vendedor_codigo" required>
          <option value=''>Seleccione</option>
          <?php foreach($vendedores as $v): ?>
            <option value="<?= htmlspecialchars($v['codigo']) ?>"><?= htmlspecialchars($v['codigo'].' - '.$v['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <label>Código Cliente</label>
    <input name="codigo_cliente">

    <label>Nombre Cliente</label>
    <input name="nombre_cliente" required>

    <label>Cédula / RUC</label>
    <input name="cedula_ruc">

    <div class="row">
      <div>
        <label>Producto</label>
        <select name="producto" required>
          <option value=''>Seleccione</option>
          <?php foreach($inventario as $p): ?>
            <option value="<?= htmlspecialchars($p['producto']) ?>"><?= htmlspecialchars($p['producto']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label>Cantidad</label>
        <input type="number" name="cantidad" min="1" value="1" required>
      </div>
    </div>

    <label>Descripción</label>
    <textarea name="descripcion"></textarea>

    <label>Recibido por</label>
    <input name="recibido_por">

    <div style="margin-top:8px">
      <button type="submit">Guardar Acta</button>
      <button type="reset">Limpiar</button>
    </div>
  </form>
</div>

<div class="card">
  <h3>Reporte General</h3>
  <table class="table">
    <thead><tr><th>N°</th><th>Fecha</th><th>Cliente</th><th>Vendedor</th><th>Producto</th><th>Cantidad</th><th>Acciones</th></tr></thead>
    <tbody>
      <?php foreach($actas as $i=>$a): ?>
        <tr>
          <td><?= htmlspecialchars('ACTA-'.str_pad($i+1,3,'0',STR_PAD_LEFT)) ?></td>
          <td><?= htmlspecialchars($a['fecha']) ?></td>
          <td><?= htmlspecialchars($a['nombre_cliente']) ?></td>
          <td><?= htmlspecialchars($a['vendedor_codigo']) ?></td>
          <td><?= htmlspecialchars($a['producto']) ?></td>
          <td><?= htmlspecialchars($a['cantidad']) ?></td>
          <td class="actions">
            <form action="actions.php" method="post" style="display:inline">
              <input type="hidden" name="action" value="print_acta">
              <input type="hidden" name="id" value="<?= $a['id'] ?>">
              <button type="submit">Imprimir</button>
            </form>
            <form action="actions.php" method="post" style="display:inline">
              <input type="hidden" name="action" value="delete_acta">
              <input type="hidden" name="id" value="<?= $a['id'] ?>">
              <button type="submit" onclick="return confirm('Eliminar acta?')">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="card">
  <h3>Inventario</h3>
  <table class="table">
    <thead><tr><th>Producto</th><th>Stock Inicial</th><th>Entradas</th><th>Salidas</th><th>Stock Actual</th></tr></thead>
    <tbody>
      <?php foreach($inventario as $p): $actual = $p['stock_inicial'] + $p['entradas'] - $p['salidas']; ?>
        <tr>
          <td><?= htmlspecialchars($p['producto']) ?></td>
          <td><?= htmlspecialchars($p['stock_inicial']) ?></td>
          <td><?= htmlspecialchars($p['entradas']) ?></td>
          <td><?= htmlspecialchars($p['salidas']) ?></td>
          <td><?= htmlspecialchars($actual) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="card">
  <h3>Vendedores</h3>
  <table class="table">
    <thead><tr><th>Código</th><th>Nombre</th></tr></thead>
    <tbody>
      <?php foreach($vendedores as $v): ?>
        <tr><td><?= htmlspecialchars($v['codigo']) ?></td><td><?= htmlspecialchars($v['nombre']) ?></td></tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
