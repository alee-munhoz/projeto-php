<?php
require 'db.php';

$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja natalina</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="top-bar">
    FAÇA SEU PEDIDO ONLINE
  </div>

  <header>

    <div class="header-title">A SUA BOUTIQUE DE NATAL</div>
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

<header class="header">
    <div class="header-content" >
      <h1>Especial de Festas</h1>
      <p>Sua maior fonte para artigos de Natal</p>
      <p>Veja todos nossos produtos</p>
      <button class="cta-button"><a href="produtos.php">Compre Agora</a></button>
    </div>
  </header>
  
  <section class="categories">
    <div class="category">
      <img src="https://static.wixstatic.com/media/84770f_3d61665c560442849e3ac2f6f7675427~mv2_d_1500_1500_s_2.jpg/v1/fill/w_303,h_432,al_c,q_80,usm_0.66_1.00_0.01,enc_avif,quality_auto/84770f_3d61665c560442849e3ac2f6f7675427~mv2_d_1500_1500_s_2.jpg" alt="Árvores de Natal">
      <h2>Árvores de Natal</h2>
      <a href="arvores.php"><button class="category-button">Comprar Coleção</button></a>
    </div>
    <div class="category">
      <img src="https://static.wixstatic.com/media/84770f_0a0ede78262c4c5e86861c5c598e82e6~mv2_d_1500_1500_s_2.jpg/v1/fill/w_318,h_432,al_c,q_80,usm_0.66_1.00_0.01,enc_avif,quality_auto/84770f_0a0ede78262c4c5e86861c5c598e82e6~mv2_d_1500_1500_s_2.jpg alt="Enfeites Especiais">
      <h2>Enfeites Especiais</h2>
      <a href="enfeites.php"><button class="category-button">Comprar Coleção</button></a>
    </div>
    <div class="category">
      <img src="https://static.wixstatic.com/media/84770f_8ef70e422ee7477b8237772b4cd69cd3~mv2_d_1500_1500_s_2.jpg/v1/fill/w_303,h_432,al_c,q_80,usm_0.66_1.00_0.01,enc_avif,quality_auto/84770f_8ef70e422ee7477b8237772b4cd69cd3~mv2_d_1500_1500_s_2.jpg" alt="Luzes de Natal">
      <h2>Luzes de Natal</h2>
      <a href="luzes.php"><button class="category-button">Comprar Coleção</button></a>
    </div>
  </section>

  <section class="promo-section">
  <div class="promo-item">
    <img src="https://static.wixstatic.com/media/84770f_2d42248cf58941ffbaf7635cc5104abe~mv2_d_1920_1280_s_2.jpg/v1/fill/w_502,h_520,al_c,q_80,usm_0.66_1.00_0.01,enc_avif,quality_auto/84770f_2d42248cf58941ffbaf7635cc5104abe~mv2_d_1920_1280_s_2.jpg" alt="Luzes nas mãos">
  </div>
  <div class="promo-item promo-message">
    <h2>10% DE DESCONTO PARA PEDIDOS!</h2>
    <p>~</p>
    <p>USE O CÓDIGO</p>
    <h3>HOHOHO</h3>
    <button class="promo-button"><a href="produtos.php">Compre Já</a></button>
  </div>
  <div class="promo-item">
    <img src="https://static.wixstatic.com/media/84770f_7dd192db00dc4d219b7fcad2061e2fcb~mv2_d_1920_1920_s_2.jpg/v1/fill/w_502,h_520,al_c,q_80,usm_0.66_1.00_0.01,enc_avif,quality_auto/84770f_7dd192db00dc4d219b7fcad2061e2fcb~mv2_d_1920_1920_s_2.jpg" alt="Guirlanda de Natal">
  </div>
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
    <p>© 2024 por A Sua Boutique de Natal. Projeto orgulhosamente criado por Alexandre e Henrique - DSM 2 - DW2 - Professora Eulaliane</p>
    </div>
</footer>
</body>
</html>