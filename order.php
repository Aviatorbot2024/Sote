<?php
// api/order.php

// Recebe os dados do pedido do frontend
$orderData = json_decode(file_get_contents('php://input'), true);

// Simula um processo simples de armazenamento em arquivo JSON
$orderFile = 'orders.json';

// Lê os pedidos existentes
if (file_exists($orderFile)) {
    $orders = json_decode(file_get_contents($orderFile), true);
} else {
    $orders = [];
}

// Adiciona o novo pedido
$orders[] = [
    'items' => $orderData['items'],
    'total' => $orderData['total'],
    'status' => 'Pendente',
    'whatsappNumber' => $orderData['whatsappNumber'],
    'created_at' => date('Y-m-d H:i:s')
];

// Salva os pedidos de volta no arquivo
file_put_contents($orderFile, json_encode($orders, JSON_PRETTY_PRINT));

// Envia a mensagem de confirmação para o cliente via WhatsApp
$message = "Seu pedido foi confirmado! Itens: {$orderData['items']}. Total: {$orderData['total']} MT";
$url = "https://wa.me/{$orderData['whatsappNumber']}?text=" . urlencode($message);

// Retorna a resposta ao frontend
echo json_encode([
    'message' => 'Pedido realizado com sucesso!',
    'url' => $url
]);
?>