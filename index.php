<?php
// admin/index.php

$orderFile = '../api/orders.json';

if (file_exists($orderFile)) {
    $orders = json_decode(file_get_contents($orderFile), true);
} else {
    $orders = [];
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração</title>
</head>
<body>
    <h1>Painel de Administração</h1>

    <?php if (empty($orders)): ?>
        <p>Não há pedidos.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($orders as $order): ?>
                <li>
                    <p><strong>Itens:</strong> <?= $order['items'] ?></p>
                    <p><strong>Total:</strong> <?= $order['total'] ?> MT</p>
                    <p><strong>Status:</strong> <?= $order['status'] ?></p>
                    <p><strong>Data:</strong> <?= $order['created_at'] ?></p>
                    <button onclick="approveOrder(<?= $order['status'] ?>)">Aprovar Pedido</button>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>