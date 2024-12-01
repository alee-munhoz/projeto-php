<?php 
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['add'])) {
    $product_id = $_GET['add'];
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $cart_item = $stmt->fetch();

    if ($cart_item) {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->execute([$user_id, $product_id]);
    }

    header("Location: carrinho.php");
    exit();
}

if (isset($_GET['remove'])) {
    $cart_id = $_GET['remove'];
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->execute([$cart_id]);

    header("Location: carrinho.php");
    exit();
}

if (isset($_GET['increase'])) {
    $cart_id = $_GET['increase'];
    $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id = ?");
    $stmt->execute([$cart_id]);

    header("Location: carrinho.php");
    exit();
}

if (isset($_GET['decrease'])) {
    $cart_id = $_GET['decrease'];
    // Verifica se a quantidade é maior que 1 antes de diminuir
    $stmt = $pdo->prepare("SELECT quantity FROM cart WHERE id = ?");
    $stmt->execute([$cart_id]);
    $item = $stmt->fetch();

    if ($item['quantity'] > 1) {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity - 1 WHERE id = ?");
        $stmt->execute([$cart_id]);
    } else {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ?");
        $stmt->execute([$cart_id]);
    }

    header("Location: carrinho.php");
    exit();
}

$stmt = $pdo->prepare("SELECT c.id, p.name, p.price, c.quantity 
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?");
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
    <link rel="stylesheet" href="style.css">
    <title>Carrinho</title>
    <style>
        .cart-table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        .cart-table th, .cart-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .cart-table th {
            background-color: #f4f4f4;
        }

        .btn-remove {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-remove:hover {
            background-color: #c82333;
        }

        .btn-checkout {
            display: block;
            margin: 20px auto;
            text-align: center;
            background: #28a745;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-checkout:hover {
            background: #218838;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-controls button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 5px 10px;
            font-size: 16px;
            cursor: pointer;
        }

        .quantity-controls button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<header>
    <div class="header-title"><a href="index.php">A SUA BOUTIQUE DE NATAL</a></div>
    <nav class="nav-bar">
        <a href="arvores.php">ÁRVORES DE NATAL</a>
        <a href="enfeites.php">ENFEITES ESPECIAIS</a>
        <a href="luzes.php">LUZES DE NATAL</a>
        <a href="sobre.html">SOBRE</a>
        <a href="login.php">LOGIN</a>
    </nav>
    <div class="cart">
        <a class="cart" href="carrinho.php"><b>CARRINHO</b></a>
    </div>
</header>
<header>
    <h1>Seu Carrinho</h1>
</header>

<main>
    <table class="cart-table">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Preço Unitário</th>
                <th>Quantidade</th>
                <th>Total</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td>R$ <?= number_format($item['price'], 2, ',', '.') ?></td>
                <td>
                    <div class="quantity-controls">
                        <!-- Botões de aumentar e diminuir -->
                        <a href="carrinho.php?increase=<?= $item['id'] ?>"><button>↑</button></a>
                        <span><?= $item['quantity'] ?></span>
                        <a href="carrinho.php?decrease=<?= $item['id'] ?>"><button>↓</button></a>
                    </div>
                </td>
                <td>R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></td>
                <td>
                    <a href="carrinho.php?remove=<?= $item['id'] ?>" class="btn-remove">Remover</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h3>Total: R$ <?= number_format($total_price, 2, ',', '.') ?></h3>
    <a href="checkout.php" class="btn-checkout">Finalizar Compra</a>
</main>
</body>
</html>
