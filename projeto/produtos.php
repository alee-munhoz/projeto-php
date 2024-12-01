<?php
require_once 'db.php';

$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'ASC';

$query = "SELECT id, name, description, price, image, category FROM products WHERE category LIKE :category";
if ($order_by == 'ASC') {
    $query .= " ORDER BY price ASC";
} elseif ($order_by == 'DESC') {
    $query .= " ORDER BY price DESC";
}

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute(['category' => '%' . $category_filter . '%']);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao consultar os produtos: " . $e->getMessage());
}

$categories_query = "SELECT DISTINCT category FROM products";
$categories_stmt = $pdo->prepare($categories_query);
$categories_stmt->execute();
$categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Sua Boutique de Natal</title>
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

    .filter-section {
        margin: 20px 0;
        display: flex;
        justify-content: center; 
        align-items: center;
        gap: 10px;
    }

    .filter-section select, .filter-section button {
        padding: 10px;
        font-size: 1rem;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .filter-section button {
        background-color: #28a745;
        color: white;
        cursor: pointer;
    }

    .filter-section button:hover {
        background-color: #218838;
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
    <!-- Filtro e Ordenação -->
    <section class="filter-section">
        <form method="get" action="produtos.php" style="display: flex; gap: 10px;">
            <select name="category">
                <option value="">Selecione uma categoria</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['category'] ?>" <?= $category['category'] == $category_filter ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['category']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="order_by">
                <option value="ASC" <?= $order_by == 'ASC' ? 'selected' : '' ?>>Preço: Menor para Maior</option>
                <option value="DESC" <?= $order_by == 'DESC' ? 'selected' : '' ?>>Preço: Maior para Menor</option>
            </select>

            <button type="submit">Filtrar e Ordenar</button>
        </form>
    </section>

    <!-- Exibição dos Produtos -->
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
