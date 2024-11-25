<?php
$host = 'localhost';
$usuario = 'root';  // Substitua pelo seu usuário do MySQL
$senha = '';        // Substitua pela sua senha do MySQL
$banco = 'barbearia';

// Criar conexão
$conexao = new mysqli($host, $usuario, $senha, $banco);

// Verificar conexão
if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conexao->real_escape_string($_POST['email']);
    $senha = $_POST['password'];

    // Consulta para verificar credenciais
    $sql = "SELECT id, nome, senha FROM clientes WHERE email = '$email'";
    $resultado = $conexao->query($sql);

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        
        // Verificar senha (você deve usar password_hash() no cadastro)
        if (password_verify($senha, $usuario['senha'])) {
            // Login bem-sucedido
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            
            // Redirecionar para página inicial
            header("Location: agenda.html");
            exit();
        } else {
            $erro = "Senha incorreta";
        }
    } else {
        $erro = "Usuário não encontrado";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css">
    <title>Login Barbearia</title>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="img/logo.png" alt="Barbearia Style">
        </div>
        <h1>Zeca Styles</h1>
        
        <?php if(isset($erro)): ?>
            <div class="error-message"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
        <div class="forgot-password">
            <a href="recuperar_senha.php">Esqueceu sua senha?</a>
        </div>
    </div>
</body>
</html>