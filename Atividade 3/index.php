<?php
$host = "localhost";
$user = "root";
$pass = "root"; 
$dbname = "sistema_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $conn->real_escape_string($_POST['nome']);
    $tipo = $conn->real_escape_string($_POST['tipo']);
    $cpf_cnpj = $conn->real_escape_string($_POST['cpf_cnpj']);

    if (empty($nome) || empty($tipo) || empty($cpf_cnpj)) {
        $mensagem = "<span class='erro'>Erro: Todos os campos são obrigatórios.</span>";
    } else {
        
        $sql = "INSERT INTO usuarios (nome, tipo_pessoa, cpf_cnpj) VALUES ('$nome', '$tipo', '$cpf_cnpj')";
        
        if ($conn->query($sql) === TRUE) {
            $mensagem = "<span class='sucesso'>Dados inseridos com sucesso!</span>";
        } else {
            $mensagem = "<span class='erro'>Erro ao inserir: " . $conn->error . "</span>";
        }
    }
}


$resultado = $conn->query("SELECT * FROM usuarios");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Atividade Prática Diagnóstica - IFMG</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .erro { color: red; font-weight: bold; }
        .sucesso { color: green; font-weight: bold; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        form { margin-bottom: 30px; border: 1px solid #ddd; padding: 15px; width: 400px; }
    </style>
</head>
<body>

    <h1>Exercício de Integração </h1>

    <div><?php echo $mensagem; ?></div>

    <form id="f" method="post" action="index.php">
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" size="40" maxlength="40"><br>
        <span id="msg-nome" class="erro"></span><br>

        <label>Tipo de Pessoa:</label><br>
        <input type="radio" id="pfisica" name="tipo" value="fisica">
        <label for="pfisica">Fisica</label>
        <input type="radio" id="pjuridica" name="tipo" value="juridica">
        <label for="pjuridica">Jurídica</label><br>
        <span id="msg-tipo" class="erro"></span><br>

        <label for="cpf_cnpj">CPF/CNPJ:</label><br>
        <input type="text" id="cpf_cnpj" name="cpf_cnpj"><br>
        <span id="msg-cpf_cnpj" class="erro"></span><br>

        <input type="submit" value="   Enviar   ">
        <input type="reset" value="   Limpar   ">
    </form>

    <h2>Dados Cadastrados</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Tipo de Pessoa</th>
                <th>CPF/CNPJ</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows > 0): ?>
                <?php while($row = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nome']; ?></td>
                        <td><?php echo ucfirst($row['tipo_pessoa']); ?></td>
                        <td><?php echo $row['cpf_cnpj']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Nenhum registro encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script src="script.js" defer></script>

</body>
</html>

