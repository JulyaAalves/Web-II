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


$nome_digitado = "";
$tipo_digitado = "";
$cpf_cnpj_digitado = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nome_digitado = $conn->real_escape_string(trim($_POST['nome'] ?? ''));
    $tipo_digitado = $conn->real_escape_string($_POST['tipo'] ?? '');
    
   
    $cpf_cnpj_digitado = preg_replace('/\D/', '', $_POST['cpf_cnpj'] ?? '');

    if (empty($nome_digitado) || empty($tipo_digitado) || empty($cpf_cnpj_digitado)) {
        $mensagem = "<span class='erro'>Erro: Todos os campos são obrigatórios.</span>";
    } 
    elseif ($tipo_digitado == 'fisica' && strlen($cpf_cnpj_digitado) != 11) {
        $mensagem = "<span class='erro'>Erro: CPF deve ter 11 dígitos.</span>";
    } 
    elseif ($tipo_digitado == 'juridica' && strlen($cpf_cnpj_digitado) != 14) {
        $mensagem = "<span class='erro'>Erro: CNPJ deve ter 14 dígitos.</span>";
    } 
    else {
        $sql = "INSERT INTO usuarios (nome, tipo_pessoa, cpf_cnpj) VALUES ('$nome_digitado', '$tipo_digitado', '$cpf_cnpj_digitado')";
        
        if ($conn->query($sql) === TRUE) {
            $mensagem = "<span class='sucesso'>Dados inseridos com sucesso!</span>";
            
            
            $nome_digitado = "";
            $tipo_digitado = "";
            $cpf_cnpj_digitado = "";
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

    <h1>Exercício de Integração</h1>

    <div><?php echo $mensagem; ?></div>

    <form id="f" method="post" action="index.php">
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" size="40" maxlength="40" value="<?php echo htmlspecialchars($nome_digitado); ?>"><br>
        <span id="msg-nome" class="erro"></span><br>

        <label>Tipo de Pessoa:</label><br>
        <input type="radio" id="pfisica" name="tipo" value="fisica" <?php if($tipo_digitado == 'fisica') echo 'checked'; ?>>
        <label for="pfisica">Física</label>
        
        <input type="radio" id="pjuridica" name="tipo" value="juridica" <?php if($tipo_digitado == 'juridica') echo 'checked'; ?>>
        <label for="pjuridica">Jurídica</label><br>
        <span id="msg-tipo" class="erro"></span><br>

        <label for="cpf_cnpj">CPF/CNPJ:</label><br>
        <input type="text" id="cpf_cnpj" name="cpf_cnpj" value="<?php echo htmlspecialchars($cpf_cnpj_digitado); ?>"><br>
        <span id="msg-cpf_cnpj" class="erro"></span><br>

        <input type="submit" value="   Enviar   ">
        <input type="button" value="   Limpar   " onclick="window.location.href='index.php';">
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
                        <td><?php echo htmlspecialchars($row['nome']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($row['tipo_pessoa'])); ?></td>
                        <td><?php echo htmlspecialchars($row['cpf_cnpj']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Nenhum registro encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
        
        document.getElementById("f").addEventListener("submit", function (e) {
            let valido = true;
            const nome = document.getElementById("nome");
            const pfisica = document.getElementById("pfisica");
            const pjuridica = document.getElementById("pjuridica");
            const cpfCnpj = document.getElementById("cpf_cnpj");
            
            // Limpa as mensagens de erro anteriores do JS
            document.querySelectorAll(".erro").forEach(span => {
               
                if(span.id.startsWith("msg-")) {
                    span.innerHTML = "";
                }
            });

            if (nome.value.trim() === "") {
                document.getElementById("msg-nome").innerHTML = "O nome é obrigatório.";
                valido = false;
            }

            if (!pfisica.checked && !pjuridica.checked) {
                document.getElementById("msg-tipo").innerHTML = "Selecione o tipo de pessoa.";
                valido = false;
            }

            const numeros = cpfCnpj.value.replace(/\D/g, '');
            
            if (numeros === "") {
                document.getElementById("msg-cpf_cnpj").innerHTML = "O campo CPF/CNPJ não pode estar vazio.";
                valido = false;
            } else if (pfisica.checked && numeros.length !== 11) {
                document.getElementById("msg-cpf_cnpj").innerHTML = "CPF deve ter 11 dígitos.";
                valido = false;
            } else if (pjuridica.checked && numeros.length !== 14) {
                document.getElementById("msg-cpf_cnpj").innerHTML = "CNPJ deve ter 14 dígitos.";
                valido = false;
            }

            if (!valido) {
                e.preventDefault(); 
            }
        });
    </script>
</body>
</html>
