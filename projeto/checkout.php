<?php 
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $shipping_address = $_POST['shipping_address'];
    $payment_method = $_POST['payment_method'];

    // Criar pedido com método de pagamento
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, shipping_address, payment_method) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $_POST['total_price'], $shipping_address, $payment_method]);
    $order_id = $pdo->lastInsertId();

    // Adicionar itens ao pedido
    $stmt = $pdo->prepare("SELECT p.id, p.name, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll();

    foreach ($cart_items as $item) {
        // Inserir itens do pedido, agora com a chave price corretamente obtida
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
    }

    // Limpar carrinho
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);

    header("Location: historico.php");
    exit();
}

// Obter itens do carrinho para cálculo
$stmt = $pdo->prepare("SELECT p.name, c.quantity, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

$total_price = array_reduce($cart_items, function ($sum, $item) {
    return $sum + ($item['price'] * $item['quantity']);
}, 0);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        h1, h3 {
            color: #333;
            text-align: center;
        }

        form {
            background-color: white;
            padding: 30px;
            max-width: 600px;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 5px;
        }

        textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        button {
            width: 100%;
            padding: 15px;
            background-color: #e60023;
            color: white;
            font-size: 1.2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #c5001e;
        }

        .total-price {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<form method="post">
    <h1>Finalizar Compra</h1>
    <div class="total-price">
        <h3>Total: R$ <?= number_format($total_price, 2, ',', '.') ?></h3>
    </div>

    <input type="hidden" name="total_price" value="<?= $total_price ?>">

    <div class="form-group">
        <label for="shipping_address">Endereço de Entrega:</label>
        <textarea name="shipping_address" required></textarea>
    </div>

    <div class="form-group">
        <label for="payment_method">Forma de Pagamento:</label>
        <select name="payment_method" required>
            <option value="pix">PIX</option>
            <option value="boleto">Boleto</option>
            <option value="cartao">Cartão de Crédito</option>
        </select>
    </div>

    <button type="submit">Confirmar Compra</button>
</form>
</body>
</html>
