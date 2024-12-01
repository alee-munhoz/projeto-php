<?php
require_once 'db.php'; 

$order = isset($_GET['order']) ? $_GET['order'] : 'asc';
$order_by = $order === 'desc' ? 'DESC' : 'ASC';

try {
    $query = $pdo->prepare("SELECT id, name, description, price, image FROM products WHERE category = 'luzes' ORDER BY price $order_by");
    $query->execute();
    $produtos = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao consultar os produtos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luzes de Natal</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .products-section {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 40px 20px;
            flex-wrap: wrap;
        }
        .product-card {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: calc(33.333% - 20px);
            box-sizing: border-box;
            position: relative;
        }
        .product-card img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .product-card h3 {
            margin: 15px 0;
            font-size: 1.2rem;
            color: #333;
        }
        .product-card p {
            font-size: 1rem;
            color: #666;
            margin-bottom: 10px;
        }
        .product-card strong {
            font-size: 1.2rem;
            color: #e60023;
        }

        .add-to-cart-btn {
            background-color: #e60023; 
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            margin-top: 15px;
            width: 100%;
            text-align: center;
            text-decoration: none; 
            transition: all 0.3s ease-in-out;
        }

        .add-to-cart-btn:hover {
            background-color: #c8001f; 
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); 
        }

        .add-to-cart-btn:active {
            transform: scale(1);
        }

        .filter-form {
            margin: 20px 0;
            text-align: center;
        }
        .filter-form select {
            padding: 5px;
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .product-card {
                width: calc(50% - 20px);
            }
        }
        @media (max-width: 480px) {
            .product-card {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="top-bar">
    FAÇA SEU PEDIDO ONLINE
</div>
<header>
    <div class="header-title"><a href="index.php">A SUA BOUTIQUE DE NATAL</a></div>
    <nav class="nav-bar">
        <a href="arvores.php">ÁRVORES DE NATAL</a>
        <a href="enfeites.php">ENFEITES ESPECIAIS</a>
        <a href="luzes.php">LUZES DE NATAL</a>
        <a href="produtos.php">TODOS OS PRODUTOS</a>
        <a href="sobre.html">SOBRE</a>
        <a href="login.php">LOGIN</a>
    </nav>
    <div class="cart">
        <a class="cart" href="carrinho.php"><b>CARRINHO</b></a>
    </div>
</header>
<main>
    <!-- Filtro de Ordenação -->
    <div class="filter-form">
        <form method="GET" action="luzes.php">
            <label for="order">Ordenar por preço:</label>
            <select name="order" id="order" onchange="this.form.submit()">
                <option value="asc" <?= $order === 'asc' ? 'selected' : '' ?>>Menor para Maior</option>
                <option value="desc" <?= $order === 'desc' ? 'selected' : '' ?>>Maior para Menor</option>
            </select>
        </form>
    </div>

    <section class="products-section">
        <?php if (!empty($produtos)): ?>
            <?php foreach ($produtos as $produto): ?>
                <div class="product-card">
                    <img src="<?= htmlspecialchars($produto['image']) ?>" alt="<?= htmlspecialchars($produto['name']) ?>">
                    <h3><?= htmlspecialchars($produto['name']) ?></h3>
                    <p><?= htmlspecialchars($produto['description']) ?></p>
                    <p><strong>R$ <?= number_format($produto['price'], 2, ',', '.') ?></strong></p>
                    <a href="carrinho.php?add=<?= $produto['id'] ?>" class="add-to-cart-btn">Adicionar ao Carrinho</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Não há produtos disponíveis nesta categoria.</p>
        <?php endif; ?>
    </section>
</main>
<footer>
    <div class="footer-logo">
        <a href="index.php"><h2>A SUA BOUTIQUE DE NATAL</h2></a>
    </div>
    <nav class="footer-nav">
        <a href="arvores.php">ÁRVORES DE NATAL</a>
        <a href="enfeites.php">ENFEITES ESPECIAIS</a>
        <a href="luzes.php">LUZES DE NATAL</a>
        <a href="produtos.php">TODOS OS PRODUTOS</a>
        <a href="sobre.html">SOBRE</a>
        <a href="login.php">LOGIN</a>
    </nav>
    <div class="footer-info">
        <p>© 2024 por A Sua Boutique de Natal. Projeto orgulhosamente criado por Alexandre e Henrique.</p>
    </div>
</footer>
</body>
</html>