<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $address = $_POST['address'];

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, address) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $address]);

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #666;
            color: #333;
            margin: 0;
            padding: 0;
            background-image: url('https://www.transparenttextures.com/patterns/diamond-tile.png');
        }

        .logo-link a {
            color: #fff;
            text-decoration: none;
            font-size: 2.5em;
            font-weight: bold;
            text-align: center;
            display: block;
            margin-top: 20px;
        }

        .register-form {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            border: 2px solid #9b111e;
            border-radius: 10px;
            background: #ffdfdf;
            background-image: url('https://www.transparenttextures.com/patterns/trees.png');
            background-repeat: no-repeat;
            background-size: cover;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .register-form h1 {
            text-align: center;
            color: #9b111e;
            font-size: 2em;
            font-family: 'Cursive', sans-serif;
            margin-bottom: 30px;
            color: #d82c1d;
        }

        .register-form input, .register-form textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .register-form button {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #9b111e;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .register-form button:hover {
            background-color: #d82c1d;
        }

        .back-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #34a853;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #56bb4d;
        }

        .error {
            color: red;
            font-size: 14px;
        }

        .snowflakes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
            overflow: hidden;
        }

        .snowflake {
            position: absolute;
            color: #fff;
            font-size: 30px;
            user-select: none;
            animation: snowfall linear infinite;
        }

        @keyframes snowfall {
            0% {
                transform: translateY(-100px);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh);
                opacity: 0;
            }
        }

        .snowflake:nth-child(odd) {
            animation-duration: 10s;
        }

        .snowflake:nth-child(even) {
            animation-duration: 12s;
        }
    </style>
</head>
<body>

<div class="snowflakes">
    <div class="snowflake" style="left: 5%; animation-duration: 10s;">❄</div>
    <div class="snowflake" style="left: 25%; animation-duration: 15s;">❄</div>
    <div class="snowflake" style="left: 45%; animation-duration: 12s;">❄</div>
    <div class="snowflake" style="left: 65%; animation-duration: 14s;">❄</div>
    <div class="snowflake" style="left: 85%; animation-duration: 11s;">❄</div>
</div>

<div class="logo-link">
    <a href="index.php">A SUA BOUTIQUE DE NATAL</a>
</div>

<form class="register-form" method="post">
    <h1>Cadastro</h1>
    <label for="name">Nome:</label>
    <input type="text" name="name" required>

    <label for="email">E-mail:</label>
    <input type="email" name="email" required>

    <label for="password">Senha:</label>
    <input type="password" name="password" required>

    <label for="address">Endereço:</label>
    <textarea name="address" required></textarea>

    <button type="submit">Cadastrar</button>

    <a href="login.php">
        <button type="button" class="back-btn">Já tem uma conta? Faça login</button>
    </a>
</form>

</body>
</html>
