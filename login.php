<?php
session_start();

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "projeto01";

try {

    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        try {
            
            $stmt = $pdo->prepare("SELECT id, senha FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            
            if (!$user) {
                $error = "Usuário não encontrado.";
            } else {
                
                if (($password === $user['senha'])) {
                    
                    $_SESSION['user_id'] = $user['id'];
                    header("Location:index.php");
                    exit;
                } else {
                    $error = "Senha incorreta.";
                }
            }
        } catch (PDOException $e) {
            $error = "Erro ao realizar login: " . $e->getMessage();
        }
    } else {
        $error = "Por favor, preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Acesso</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #7f7ff8, #ddddff);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .logo {
            margin-bottom: 1.5rem;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h1 {
            color: #003da5;
            margin-bottom: 1.5rem;
        }
        .input-group {
            margin-bottom: 1rem;
            position: relative;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }
        input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        button {
            background-color: #003da5;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #002d8a;
        }
        .error-message {
            color: #ff3860;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
        .password-toggle i {
            position: absolute;
            top: 70%;
            right: 1rem;
            transform: translateY(-50%);
            cursor: pointer;
            color: #003da5;
        }
        .password-toggle i:hover {
            color: #002d8a;
        }
    </style>
</head>
<body>
    <img class="logo" src="favicons/android-chrome-192x192.png" alt="Logotipo" width="200" height="200">
    <div class="login-container">
        <h1>Bem-vindo</h1>
        <form action="login.php" method="post" id="loginForm">
            <div class="input-group">
                <label for="username">Usuário</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group password-toggle">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
                <i class="fas fa-eye" id="togglePassword"></i>
            </div>
            <button type="submit">Entrar</button>
        </form>
        <p class="error-message">
            <?php if (!empty($error)) echo htmlspecialchars($error); ?>
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>
</html>