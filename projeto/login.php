<?php  
session_start();
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email === 'admin@admin' && $password === 'admin') {
        $_SESSION['is_admin'] = true;
        $_SESSION['name'] = 'Administrador'; 
        header("Location: admin.php"); 
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['is_admin'] = false; 
        header("Location: index.php"); 
        exit();
    } else {
        $error_message = "E-mail ou senha incorretos.";
    }
}
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #666;
            color: #333;
            margin: 0;
            padding: 0;
            background-image: url('https://www.transparenttextures.com/patterns/diamond-tile.png');
            background-color: #666;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100vh;
            align-items: center;
        }

        .logo-link {
            text-align: center;
            margin-top: 50px;
            font-size: 2em;
        }

        .logo-link a {
            text-decoration: none; 
            color: #fff; 
        }

        h1 {
            text-align: center;
            color: #9b111e;
            font-size: 2.5em;
            margin-top: 50px;
            font-family: 'Cursive', sans-serif;
        }

        .login-form {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 0 auto;
            padding: 40px 30px;
            text-align: center;
        }

        .login-form label {
            display: block;
            margin-bottom: 10px;
            color: #9b111e;
            font-weight: bold;
        }

        .login-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .login-form button {
            background-color: #9b111e;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .login-form button:hover {
            background-color: #d82c1d;
        }

        .register-btn {
            background-color: #34a853;
            color: white;
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .register-btn:hover {
            background-color: #56bb4d;
        }

        .error {
            color: red;
            font-size: 14px;
        }

        .login-form::before {
            content: '🎄';
            font-size: 2em;
            display: block;
            margin-bottom: 10px;
            color: #9b111e;
        }

        .login-form h1 {
            margin:10px;
            color: #9b111e;
            font-family: 'Cursive', sans-serif;
        }

        .login-form {
            background-color: #ffdfdf;
            background-image: url('https://www.transparenttextures.com/patterns/trees.png');
            background-repeat: no-repeat;
            background-size: cover;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .logout a {
            margin:2px;
    text-decoration: none; 
    color: inherit; 
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
            font-size: 24px;
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
        <div class="snowflake" style="left: 5%; animation-duration: 10s; font-size: 30px;">❄</div>
        <div class="snowflake" style="left: 25%; animation-duration: 15s; font-size: 25px;">❄</div>
        <div class="snowflake" style="left: 45%; animation-duration: 12s; font-size: 28px;">❄</div>
        <div class="snowflake" style="left: 65%; animation-duration: 14s; font-size: 22px;">❄</div>
        <div class="snowflake" style="left: 85%; animation-duration: 11s; font-size: 32px;">❄</div>
    </div>

    <div class="logo-link">
        <a href="index.php">A SUA BOUTIQUE DE NATAL</a>
    </div>

    <form class="login-form" method="post">
        <h1>Login</h1>
        <label for="email">E-mail:</label>
        <input type="email" name="email" required>
        <label for="password">Senha:</label>
        <input type="password" name="password" required>
        <button type="submit" class="btn">Entrar</button>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?> 

        <a href="registro.php">
            <button type="button" class="register-btn">Criar uma conta</button>
        </a>
        <button class="logout"><a href="logout.php">Logout</a></button>

    </form>
</body>
</html>
