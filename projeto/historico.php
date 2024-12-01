<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ?");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Compras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 20px;
        }

        .orders-list {
            margin-top: 20px;
        }

        .order-card {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }

        .order-card h2 {
            margin-top: 0;
            font-size: 1.5em;
            color: #333;
        }

        .order-card p {
            margin: 5px 0;
            font-size: 1.1em;
        }

        .order-card strong {
            color: #007BFF;
        }

        .order-card p:last-child {
            font-size: 0.9em;
            color: #777;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.1em;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        .products-list {
            margin-top: 10px;
            font-size: 1em;
            color: #555;
        }

        .products-list li {
            margin-bottom: 5px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .order-card h2 {
                font-size: 1.2em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Histórico de Compras</h1>
        </header>
        <div class="orders-list">
            <?php if (count($orders) > 0): ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <h2>Pedido #<?= $order['id'] ?></h2>
                        <p><strong>Total:</strong> R$ <?= number_format($order['total_price'], 2, ',', '.') ?></p>
                        <p><strong>Endereço de Entrega:</strong> <?= htmlspecialchars($order['shipping_address']) ?></p>
                        <p><strong>Data do Pedido:</strong> <?= date("d/m/Y H:i", strtotime($order['created_at'])) ?></p>
                        <p><strong>Forma de Pagamento:</strong> <?= ucfirst($order['payment_method']) ?></p>

                        <!-- Exibir os itens do pedido -->
                        <?php
                            $order_id = $order['id'];
                            $stmt = $pdo->prepare("SELECT oi.quantity, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                            $stmt->execute([$order_id]);
                            $order_items = $stmt->fetchAll();
                        ?>
                        <ul class="products-list">
                            <?php foreach ($order_items as $item): ?>
                                <li><?= $item['name'] ?> (x<?= $item['quantity'] ?>)</li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Você ainda não fez nenhuma compra.</p>
            <?php endif; ?>
        </div>
        <a href="index.php" class="back-btn">Voltar para a Página Inicial</a>
    </div>
</body>
</html>