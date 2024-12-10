<?php
$servidor = "localhost";
$usuario = "root";  // ou seu usuário do MySQL
$senha = "";        // sua senha do MySQL
$banco = "barbearia";

try {
    $conexao = new PDO("mysql:host=$servidor;dbname=$banco", $usuario, $senha);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Preparar a query de inserção
        $stmt = $conexao->prepare("INSERT INTO clientes (
            nome, data_nascimento, email, telefone, senha, 
            endereco, cidade, estado, barbeiro_preferido, 
            observacoes
        ) VALUES (
            :nome, :data_nascimento, :email, :telefone, :senha,
            :endereco, :cidade, :estado, :barbeiro_preferido,
            :observacoes
        )");

        // Hash da senha
        $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);

        // Bind dos parâmetros
        $stmt->bindParam(':nome', $_POST['nome']);
        $stmt->bindParam(':data_nascimento', $_POST['dataNascimento']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':telefone', $_POST['telefone']);
        $stmt->bindParam(':senha', $senhaHash);
        $stmt->bindParam(':endereco', $_POST['endereco']);
        $stmt->bindParam(':cidade', $_POST['cidade']);
        $stmt->bindParam(':estado', $_POST['estado']);
        $stmt->bindParam(':barbeiro_preferido', $_POST['barbeiroPref']);
        $stmt->bindParam(':observacoes', $_POST['observacoes']);

        // Executar a query
        $stmt->execute();

        // Processar preferências de serviços
        if (!empty($_POST['preferencias'])) {
            $stmt = $conexao->prepare("INSERT INTO preferencias_cliente (
                cliente_id, servico
            ) VALUES (
                :cliente_id, :servico
            )");

            $clienteId = $conexao->lastInsertId();

            foreach ($_POST['preferencias'] as $servico) {
                $stmt->bindParam(':cliente_id', $clienteId);
                $stmt->bindParam(':servico', $servico);
                $stmt->execute();
            }
        }

        // Processar upload de foto
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $foto = $_FILES['foto'];
            $nome_foto = time() . '_' . $foto['name'];
            $destino = "uploads/" . $nome_foto;

            if (move_uploaded_file($foto['tmp_name'], $destino)) {
                $stmt = $conexao->prepare("UPDATE clientes SET foto = :foto WHERE id = :id");
                $stmt->bindParam(':foto', $nome_foto);
                $stmt->bindParam(':id', $clienteId);
                $stmt->execute();
            }
        }

        echo json_encode(['success' => true, 'message' => 'Cadastro realizado com sucesso!']);
        header("location:telaLoginCliente.php");
        
       

        exit();

    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro no cadastro: ' . $e->getMessage()]);
        exit();
    }
}

?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/styleCliente.css">
    <title>Cadastro de Cliente</title>
</head>
<body>
    <div class="cadastro-container">
        <div class="logo">
            <img src="caminho-para-sua-imagem.jpg" alt="Logo da Barbearia">
        </div>
        <h1>Cadastro de Cliente</h1>
        <form id="cadastroForm" method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="foto-preview" onclick="document.getElementById('foto').click()">
                <span id="fotoPlaceholder">Clique para adicionar foto</span>
                <img id="fotoPreview" src="#" alt="Prévia da foto">
            </div>
            <input type="file" id="foto" name="foto" accept="image/*" style="display: none">
            
            <div class="form-grid">
                <div class="input-group">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                
                <div class="input-group">
                    <label for="dataNascimento">Data de Nascimento</label>
                    <input type="date" id="dataNascimento" name="dataNascimento">
                </div>

                <div class="input-group">
                    <label for="email">E-mail *</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="input-group">
                    <label for="telefone">Telefone/WhatsApp *</label>
                    <input type="tel" id="telefone" name="telefone" required>
                </div>

                <div class="input-group">
                    <label for="senha">Senha *</label>
                    <input type="password" id="senha" name="senha" required>
                </div>

                <div class="input-group">
                    <label for="confirmarSenha">Confirmar Senha *</label>
                    <input type="password" id="confirmarSenha" name="confirmarSenha" required>
                </div>

                <div class="input-group full-width">
                    <label for="endereco">Endereço</label>
                    <input type="text" id="endereco" name="endereco" placeholder="Rua, número, bairro">
                </div>

                <div class="input-group">
                    <label for="cidade">Cidade</label>
                    <input type="text" id="cidade" name="cidade">
                </div>

                <div class="input-group">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado">
                        <option value="">Selecione</option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <!-- Adicione os outros estados -->
                    </select>
                </div>

                <div class="input-group full-width">
                    <label>Preferências de Serviços</label>
                    <div class="preferences-grid">
                        <div class="checkbox-group">
                            <input type="checkbox" id="corte" name="preferencias[]" value="corte">
                            <label for="corte">Corte de Cabelo</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="barba" name="preferencias[]" value="barba">
                            <label for="barba">Barba</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="pigmentacao" name="preferencias[]" value="pigmentacao">
                            <label for="pigmentacao">Pigmentação</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="hidratacao" name="preferencias[]" value="hidratacao">
                            <label for="hidratacao">Hidratação</label>
                        </div>
                    </div>
                </div>

                <div class="input-group full-width">
                    <label for="barbeiroPref">Barbeiro de Preferência</label>
                    <select id="barbeiroPref" name="barbeiroPref">
                        <option value="">Selecione um barbeiro</option>
                        <option value="1">João Silva</option>
                        <option value="2">Pedro Santos</option>
                        <option value="3">Carlos Oliveira</option>
                    </select>
                </div>

                <div class="input-group full-width">
                    <label for="observacoes">Observações</label>
                    <textarea id="observacoes" name="observacoes" rows="3" 
                        placeholder="Alguma observação especial? (Alergias, preferências específicas, etc)"></textarea>
                </div>
            </div>

            <div class="error-message" id="formError"></div>
            <div class="success-message" id="successMessage">Cadastro realizado com sucesso!</div>
            
            <button type="submit">Cadastrar</button>
        </form>
    </div>
    <script src="./JS/scriptCliente.js"></script>
</body>
</html>