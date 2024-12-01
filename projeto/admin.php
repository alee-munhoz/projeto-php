<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $image = $_POST['image'];
        $category = $_POST['category'];

        // Inserir novo produto no banco de dados
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image, category) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $image, $category]);
        $success_message = "Produto criado com sucesso!";
    }

    if (isset($_POST['delete'])) {
        $product_id = $_POST['product_id'];

        // Excluir o produto do banco de dados
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $delete_message = "Produto excluído com sucesso!";
    }

    if (isset($_POST['delete_order'])) {
        $order_id = $_POST['order_id'];

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
            $stmt->execute([$order_id]);

            $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
            $stmt->execute([$order_id]);

            $pdo->commit();

            $success_message = "Pedido excluído com sucesso!";
        } catch (Exception $e) {
            $pdo->rollBack();
            $error_message = "Erro ao excluir o pedido: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .admin-container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #007BFF;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 1.1em;
        }

        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-group button {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .success-message, .delete-message {
            color: green;
            text-align: center;
        }

        .error-message {
            color: red;
            text-align: center;
        }

        .back-button {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .back-button a {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-button a:hover {
            background-color: #0056b3;
        }

        .product-list, .order-list {
            margin-top: 40px;
        }

        .product-list table, .order-list table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-list th, .order-list th, .product-list td, .order-list td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .product-list th, .order-list th {
            background-color: #007BFF;
            color: white;
        }

        .product-list td a, .order-list td a {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }

        .product-list td a:hover, .order-list td a:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="admin-container">
    <h1>Painel Administrativo</h1>

    <?php if (isset($success_message)) { echo "<p class='success-message'>$success_message</p>"; } ?>
    <?php if (isset($delete_message)) { echo "<p class='delete-message'>$delete_message</p>"; } ?>
    <?php if (isset($error_message)) { echo "<p class='error-message'>$error_message</p>"; } ?>

    <h2>Criar Novo Produto</h2>
    <form method="POST" action="admin.php">
        <div class="form-group">
            <label for="name">Nome do Produto</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div class="form-group">
            <label for="description">Descrição</label>
            <textarea name="description" id="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="price">Preço</label>
            <input type="text" name="price" id="price" required>
        </div>
        <div class="form-group">
            <label for="image">Link da Imagem</label>
            <input type="text" name="image" id="image" required>
        </div>
        <div class="form-group">
            <label for="category">Categoria</label>
            <select name="category" id="category" required>
                <option value="arvores">Árvores</option>
                <option value="enfeites">Enfeites</option>
                <option value="luzes">Luzes</option>
            </select>
        </div>
        <div class="form-group">
            <button type="submit" name="create">Criar Produto</button>
        </div>
    </form>

    <h2>Produtos Criados</h2>
    <div class="product-list">
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Ações</th>
            </tr>
            <?php
            // Buscar todos os produtos
            $stmt = $pdo->prepare("SELECT * FROM products");
            $stmt->execute();
            $products = $stmt->fetchAll();

            foreach ($products as $product):
            ?>
            <tr>
                <td><?= $product['id'] ?></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= htmlspecialchars($product['category']) ?></td>
                <td>
                    <!-- Botão para excluir o produto -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" name="delete" onclick="return confirm('Tem certeza de que deseja excluir este produto?')">Excluir</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <h2>Pedidos</h2>
    <div class="order-list">
        <table>
            <tr>
                <th>ID</th>
                <th>Total</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
            <?php
            // Buscar todos os pedidos
            $stmt = $pdo->prepare("SELECT * FROM orders");
            $stmt->execute();
            $orders = $stmt->fetchAll();

            foreach ($orders as $order):
            ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td>R$ <?= number_format($order['total_price'], 2, ',', '.') ?></td>
                <td><?= date("d/m/Y H:i", strtotime($order['created_at'])) ?></td>
                <td>
                    <!-- Botão para excluir o pedido -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <button type="submit" name="delete_order" onclick="return confirm('Tem certeza de que deseja excluir este pedido?')">Excluir</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="form-group">
            <button><a href="index.php">Voltar</a></button>
        </div>
</div>

</body>
</html>
