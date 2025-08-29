<?php
require 'config.php';
$pdo = db();
$action = $_POST['action'] ?? '';

if ($action === 'save_acta') {
    $id = $_POST['id'] ?? null;
    $fecha = $_POST['fecha'] ?? date('Y-m-d');
    $codigo_cliente = $_POST['codigo_cliente'] ?? '';
    $nombre_cliente = $_POST['nombre_cliente'] ?? '';
    $cedula = $_POST['cedula_ruc'] ?? '';
    $vendedor = $_POST['vendedor_codigo'] ?? '';
    $producto = $_POST['producto'] ?? '';
    $cantidad = intval($_POST['cantidad'] ?? 0);
    $descripcion = $_POST['descripcion'] ?? '';
    $recibido_por = $_POST['recibido_por'] ?? '';

    if (!$nombre_cliente || !$vendedor || !$producto || $cantidad<=0) {
        header('Location: index.php'); exit;
    }

    if (empty($id)) {
        // insertar nueva acta
        $stmt = $pdo->prepare('INSERT INTO actas (fecha, codigo_cliente, nombre_cliente, cedula_ruc, vendedor_codigo, producto, cantidad, descripcion, recibido_por) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$fecha, $codigo_cliente, $nombre_cliente, $cedula, $vendedor, $producto, $cantidad, $descripcion, $recibido_por]);
    } else {
        // actualizar (no cambia número, usando id)
        $stmt = $pdo->prepare('UPDATE actas SET fecha=?, codigo_cliente=?, nombre_cliente=?, cedula_ruc=?, vendedor_codigo=?, producto=?, cantidad=?, descripcion=?, recibido_por=? WHERE id=?');
        $stmt->execute([$fecha, $codigo_cliente, $nombre_cliente, $cedula, $vendedor, $producto, $cantidad, $descripcion, $recibido_por, $id]);
    }

    // Recompute inventario salidas
    $pdo->exec('UPDATE inventario SET salidas = 0');
    $rows = $pdo->query('SELECT producto, SUM(cantidad) as total FROM actas GROUP BY producto')->fetchAll();
    $stmtUpd = $pdo->prepare('UPDATE inventario SET salidas = ? WHERE producto = ?');
    foreach($rows as $r){ $stmtUpd->execute([(int)$r['total'], $r['producto']]); }

    header('Location: index.php');
    exit;
}

if ($action === 'delete_acta') {
    $id = intval($_POST['id'] ?? 0);
    if ($id>0) {
        $pdo->prepare('DELETE FROM actas WHERE id=?')->execute([$id]);
        // resecuenciar: in MySQL we maintain ids but we need acta numbers to be continuous in display only.
        // Recompute salidas
        $pdo->exec('UPDATE inventario SET salidas = 0');
        $rows = $pdo->query('SELECT producto, SUM(cantidad) as total FROM actas GROUP BY producto')->fetchAll();
        $stmtUpd = $pdo->prepare('UPDATE inventario SET salidas = ? WHERE producto = ?');
        foreach($rows as $r){ $stmtUpd->execute([(int)$r['total'], $r['producto']]); }
    }
    header('Location: index.php');
    exit;
}

if ($action === 'print_acta') {
    $id = intval($_POST['id'] ?? 0);
    $stmt = $pdo->prepare('SELECT * FROM actas WHERE id=?');
    $stmt->execute([$id]);
    $a = $stmt->fetch();
    if ($a) {
        // simple printable page
        echo "<!doctype html><html><head><meta charset='utf-8'><title>".htmlspecialchars($a['nombre_cliente'])."</title><style>body{font-family:Arial;padding:20px}</style></head><body>";
        echo "<img src='assets/coile.webp' style='height:60px'><h2>ACTA DE ENTREGA</h2>";
        echo "<p><strong>Fecha:</strong> ".htmlspecialchars($a['fecha'])."</p>";
        echo "<p><strong>Cliente:</strong> ".htmlspecialchars($a['nombre_cliente'])." (" . htmlspecialchars($a['codigo_cliente']) .")</p>";
        echo "<p><strong>Cédula/RUC:</strong> ".htmlspecialchars($a['cedula_ruc'])."</p>";
        echo "<p><strong>Vendedor:</strong> ".htmlspecialchars($a['vendedor_codigo'])."</p>";
        echo "<p><strong>Producto:</strong> ".htmlspecialchars($a['producto'])."</p>";
        echo "<p><strong>Cantidad:</strong> ".htmlspecialchars($a['cantidad'])."</p>";
        echo "<p><strong>Recibido por:</strong> ".htmlspecialchars($a['recibido_por'])."</p>";
        echo "<script>window.print()</script></body></html>";
        exit;
    }
    header('Location: index.php');
    exit;
}

header('Location: index.php');
exit;
?>