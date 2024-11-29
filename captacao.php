<?php
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $tipoImovel = $_POST['propertyType'] ?? null;
        $endereco = $_POST['address'] ?? null;
        $area = floatval($_POST['area'] ?? 0);
        $preco = floatval($_POST['price'] ?? 0);
        $situacao = $_POST['situacao'] ?? null;
        $condominio = floatval($_POST['condominio'] ?? 0);
        $iptu = floatval($_POST['iptu'] ?? 0);

        $nomeProprietario = $_POST['ownerName'] ?? null;
        $cpfProprietario = floatval($_POST['ownerCPF'] ?? 0);
        $telefoneProprietario = floatval($_POST['ownerPhone'] ?? 0);
        $emailProprietario = $_POST['ownerEmail'] ?? null;

        $codigoVenal = null;
        $outrosDocs = null;

        if (isset($_FILES['codigoVenal']['tmp_name']) && is_uploaded_file($_FILES['codigoVenal']['tmp_name'])) {
            $codigoVenal = file_get_contents($_FILES['codigoVenal']['tmp_name']);
        }
        if (isset($_FILES['otherDocuments']['tmp_name']) && is_uploaded_file($_FILES['otherDocuments']['tmp_name'])) {
            $outrosDocs = file_get_contents($_FILES['otherDocuments']['tmp_name']);
        }

        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            INSERT INTO infProprietario (Nome, CPF, Telefone, Email) 
            VALUES (:nome, :cpf, :telefone, :email)
        ");
        $stmt->execute([
            ':nome' => $nomeProprietario,
            ':cpf' => $cpfProprietario,
            ':telefone' => $telefoneProprietario,
            ':email' => $emailProprietario,
        ]);
        $proprietarioId = $pdo->lastInsertId();

        $stmt = $pdo->prepare("
            INSERT INTO imoveis (tipoImovel, endereco, area, preco, situacao, condominio, iptu) 
            VALUES (:tipoImovel, :endereco, :area, :preco, :situacao, :condominio, :iptu)
        ");
        $stmt->execute([
            ':tipoImovel' => $tipoImovel,
            ':endereco' => $endereco,
            ':area' => $area,
            ':preco' => $preco,
            ':situacao' => $situacao,
            ':condominio' => $condominio,
            ':iptu' => $iptu,
        ]);

        $stmt = $pdo->prepare("
            INSERT INTO Docs (CodVenal, OutDocs) 
            VALUES (:codVenal, :outDocs)
        ");
        $stmt->bindParam(':codVenal', $codigoVenal, PDO::PARAM_LOB);
        $stmt->bindParam(':outDocs', $outrosDocs, PDO::PARAM_LOB);
        $stmt->execute();

        $pdo->commit();

        echo "Imóvel cadastrado com sucesso!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Erro ao cadastrar o imóvel: " . $e->getMessage();
    }
}
?>


<html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Captação de Imóveis</title>
<link rel="shortcut icon" href="favicons/favicon-16x16.png">
<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

:root {
    --nav-bg-color: #003da5;
    --nav-text-color: #e60e16; /* Mantendo o padrão de cores */
    --button-bg-color: #003da5;
    --button-hover-bg-color: #002d8a;
    --button-text-color: rgba(0, 0, 0, 0.795);
    --body-bg-color: #ffffff;
    --form-bg-color: #ffffff;
    --form-border-color: #ccc;
    --tab-bg-color: #ddd;
    --tab-active-bg-color: #003da5;
    --tab-active-text-color: #ffffff;
    --footer-bg-color: #003da5;
}

/* Reseta margens, padding e box-sizing */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    font-size: large;
    background: linear-gradient(to right, #7f7ff8, #ddddff);
    color: #000;
}

/* Estilo da navegação */
nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: var(--nav-bg-color);
    color: var(--nav-text-color);
    padding: 1px 2px;
}

.menu {
    list-style: none;
    display: flex;
    gap: 20px;
}

.menu a {
    font-size: 1.2rem;
    text-decoration: none;
    color: var(--nav-text-color);
    transition: color 0.3s;
}

.menu a:hover {
    color: aliceblue;
}

.botlog {
    display: flex;
    padding-right: 20px;
}

.button1 {
    background-color: #404cf5;
    color: var(--nav-text-color);
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    font-size: large;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

.button1:hover {
    background-color: var(--button-hover-bg-color);
    transform: scale(1.05);
}

.button1:focus {
    outline: none;
    box-shadow: 0 0 10px 2px rgba(0, 0, 0, 0.2);
}

/* Estilo do contêiner */
.container {
    text-align: center;
    font-family: 'Poppins', sans-serif;
    color: #000;
    margin: 20px auto;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    background-color: var(--form-bg-color);
    width: 90%;
    max-width: 800px;
}

/* Formulário */
form {
    background-color: var(--form-bg-color);
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    width: 100%;
    text-align: left; /* Alinha o conteúdo do formulário à esquerda */
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    text-align: left; /* Garante que os labels fiquem alinhados à esquerda */
}

input[type="text"],
input[type="email"],
textarea,
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid var(--form-border-color);
    border-radius: 4px;
    box-sizing: border-box;
    display: block; /* Garante que os inputs sejam exibidos como bloco e alinhados à esquerda */
}


/* Botão de envio */
.Enviar {
    background-color: var(--button-bg-color);
    color: var(--button-text-color);
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    font-family: 'Poppins', sans-serif;
    transition: background-color 0.3s;
}

.Enviar:hover {
    background-color: var(--button-hover-bg-color);
}

/* Títulos */
h2 {
    text-align: center;
    color: #000;
    margin: 20px 0;
}

/* Abas */
.tabs {
    display: flex;
    margin-bottom: 20px;
}

.tab {
    padding: 0.5rem 1rem;
    background: var(--tab-bg-color);
    color: var(--button-text-color);
    border-radius: 5px;
    font-size: large;
    border-width: 1px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.tab:hover {
    background-color: #b0b0b0;
}

.tab.active {
    background-color: var(--tab-active-bg-color);
    color: var(--tab-active-text-color);
}

/* Conteúdo das abas */
.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Rodapé */
footer {
    background-color: var(--footer-bg-color);
    color: var(--nav-text-color);
    padding: 20px 0;
    text-align: center;
    width: 100%;
    position: absolute;
    bottom: 0;
}

.button{
    padding: 0.5rem 1rem;
    background: var(--button-bg-color);
    color: var(--button-text-color);
    border-radius: 5px;
    font-size: large;
    border-width: 1px;
}

</style></head><body>


    <header>
        <nav> 
            <img src="favicons/android-chrome-192x192.png" alt="Logotipo" width="120"
            height="120">
        <ul class="menu">
            <li><a class="Cbç" href="index.php">Home</a></li>
            <li><a class="Cbç" href="captacao.php">Captação</a></li>
        </ul>
        <div class="botlog">
            <a href="login.php"><button class="button1">Login</button></a>
        </div>
    </nav>
    </header>

    <div class="container">
        <h1>Cadastro de Captação de Imóveis</h1>
        
        <div class="tabs">
            <button class="tab active" onclick="openTab(event, 'propertyInfo')">Informações do Imóvel</button>
            <button class="tab" onclick="openTab(event, 'ownerInfo')">Informações do Proprietário</button>
            <button class="tab" onclick="openTab(event, 'documents')">Documentos</button>
        </div>

        <form id="propertyForm" method="POST" action="captacao.php" enctype="multipart/form-data">
    <div id="propertyInfo" class="tab-content active">
        <h2>Informações do Imóvel</h2>
        <label for="propertyType">Tipo de Imóvel:</label>
        <select id="propertyType" name="propertyType" required>
            <option value=""></option>
            <option value="Casa">Casa</option>
            <option value="Apartamento">Apartamento</option>
            <option value="Terreno">Terreno</option>
            <option value="Comercial">Comercial</option>
        </select>

        <label for="address">Endereço:</label>
        <input type="text" id="address" name="address" required>

        <label for="area">Área (m²):</label>
        <input type="number" id="area" name="area" step="0.01" required>

        <label for="price">Preço (R$):</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="situacao">Situação do Imóvel:</label>
        <select id="situacao" name="situacao" required>
            <option value=""></option>
            <option value="Venda">Venda</option>
            <option value="Aluguel">Aluguel</option>
        </select>

        <label for="condominio">Condomínio Mensal (R$):</label>
        <input type="number" id="condominio" name="condominio" step="0.01" required>

        <label for="iptu">IPTU Anual (R$):</label>
        <input type="number" id="iptu" name="iptu" step="0.01" required>
    </div>

    <div id="ownerInfo" class="tab-content">
        <h2>Informações do Proprietário</h2>
        <label for="ownerName">Nome do Proprietário:</label>
        <input type="text" id="ownerName" name="ownerName" required>

        <label for="ownerCPF">CPF do Proprietário:</label>
        <input type="text" id="ownerCPF" name="ownerCPF" required>

        <label for="ownerPhone">Telefone do Proprietário:</label>
        <input type="tel" id="ownerPhone" name="ownerPhone" required>

        <label for="ownerEmail">E-mail do Proprietário:</label>
        <input type="email" id="ownerEmail" name="ownerEmail" required>
    </div>

    <div id="documents" class="tab-content">
        <h2>Documentos</h2>
        <label for="codigoVenal">Código Venal:</label>
        <input type="file" id="codigoVenal" name="codigoVenal" accept=".pdf,.doc,.docx">

        <label for="otherDocuments">Outros Documentos:</label>
        <input type="file" id="otherDocuments" name="otherDocuments" accept=".pdf,.doc,.docx" multiple>
    </div>

    <button type="submit" class="button">Cadastrar Imóvel</button>
</form>

    </div>

    <script>
        function openTab(evt, tabName) {
            var i, tabContent, tabLinks;
            tabContent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabContent.length; i++) {
                tabContent[i].style.display = "none";
            }
            tabLinks = document.getElementsByClassName("tab");
            for (i = 0; i < tabLinks.length; i++) {
                tabLinks[i].className = tabLinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        document.getElementById('propertyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const propertyType = document.getElementById('propertyType').value;
            const address = document.getElementById('address').value;
            const area = document.getElementById('area').value;
            const price = document.getElementById('price').value;
            const description = document.getElementById('description').value;
            const ownerName = document.getElementById('ownerName').value;
            const ownerCPF = document.getElementById('ownerCPF').value;
            const ownerPhone = document.getElementById('ownerPhone').value;
            const ownerEmail = document.getElementById('ownerEmail').value;
            const codigoVenal = document.getElementById('codigoVenal').files[0];

            const propertyItem = document.createElement('div');
            propertyItem.className = 'property-item';
            propertyItem.innerHTML = `
                <h3>${propertyType.charAt(0).toUpperCase() + propertyType.slice(1)}</h3>
                <p><strong>Endereço:</strong> ${address}</p>
                <p><strong>Área:</strong> ${area} m²</p>
                <p><strong>Preço:</strong> R$ ${parseFloat(price).toLocaleString('pt-BR')}</p>
                <p><strong>Descrição:</strong> ${description}</p>
                <p><strong>Proprietário:</strong> ${ownerName}</p>
                <p><strong>CPF do Proprietário:</strong> ${ownerCPF}</p>
                <p><strong>Telefone do Proprietário:</strong> ${ownerPhone}</p>
                <p><strong>E-mail do Proprietário:</strong> ${ownerEmail}</p>
                <p><strong>Código Venal:</strong> ${codigoVenal ? codigoVenal.name : 'Não enviado'}</p>
            `;

            document.getElementById('propertyList').appendChild(propertyItem);

            // Limpar o formulário
            this.reset();
        });
    </script>
</body>
<footer>
    <p>&copy; 2024 Meu Site. Todos os Direitos Reservados.</p>
</footer>

</html>