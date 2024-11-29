<?php
   session_start();
   if (!isset($_SESSION['user_id'])){
      header("Location: login.php");
      exit();    
   }

   require 'include/config.php';

   $resultado = $conexao->query("SELECT * FROM usuarios");

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="shortcut icon" href="favicons/favicon-16x16.png">
    <link rel="stylesheet" href="tela inicial.css">
</head>
<body>
    <header>
        <nav> 
            <img src="favicons/android-chrome-192x192.png" alt="Logotipo" width="120"
            height="120">
        <ul class="menu">
            <li><a class="Cbç" href="index.php">Home</a></li>
            <li><a class="Cbç" href="sobre.php">Sobre</a></li>
            <li><a class="Cbç" href="contato.php">Contato</a></li>
            <li><a class="Cbç" href="residencia.php">Residências</a></li>
            <li><a class="Cbç" href="captacao.php">Captação</a></li>
        </ul>
        <div class="botlog">
            <a href="login.php"><button class="button1">Login</button></a>
        </div>
    </nav>
    </header>
    <section class="espaco">
        <section class="hero">
            <h2>Encontre o seu imóvel dos sonhos</h2>
            <form class="search-form">
                <input type="text" placeholder="Buscar imóveis...">
                <button type="submit" class="button">Buscar</button>
            </form>
        </section>

        <h1>Propriedades em destaque</h1>
    
        <section id="properties" class="property-list">
            <div class="property-card">
                <img src="casa SP.jpg" alt="Imóvel 1" width="400" height="300">
                <h3>Casa em São Paulo</h3>
                <p>R$ 1.000.000</p>
                <button class="button">Ver Detalhes</button>
            </div>
            <div class="property-card">
                <img src="ap rj.jpg" alt="Imóvel 2" width="400" height="300">
                <h3>Apartamento no Rio de Janeiro</h3>
                <p>R$ 800.000</p>
                <button class="button">Ver Detalhes</button>
            </div>
            <div class="property-card">
                <img src="ilhabela.jpg" alt="Imóvel 3" width="400" height="300">
                <h3>Casa Ilhabela</h3>
                <p>R$ 2.900.000</p>
                <button class="button">Ver Detalhes</button>
            </div>
            <div class="property-card">
                <img src="camboriu.jpg" alt="Imóvel 4" width="400" height="300">
                <h3>Apartamento Balnario Camboriu</h3>
                <p>R$ 7.200.000</p>
                <button class="button">Ver Detalhes</button>
            </div>
        </section>
    </section>

<footer>
    <p>&copy; 2024 Meu Site. Todos os Direitos Reservados.</p>
</footer>
        
</body>
</html>