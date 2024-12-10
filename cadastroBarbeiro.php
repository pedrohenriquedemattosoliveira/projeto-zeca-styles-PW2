<?php
// Configuração do Banco de Dados
$host = 'localhost';
$dbname = 'barbearia';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Funções de Validação e Processamento
function validateCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    
    if (strlen($cpf) != 11) return false;
    if (preg_match('/^(.)\1*$/', $cpf)) return false;
    
    $sum1 = 0;
    $sum2 = 0;
    
    for ($i = 0; $i < 9; $i++) {
        $sum1 += $cpf[$i] * (10 - $i);
        $sum2 += $cpf[$i] * (11 - $i);
    }
    
    $digit1 = ($sum1 * 10) % 11;
    $digit1 = ($digit1 == 10) ? 0 : $digit1;
    
    $sum2 += $digit1 * 2;
    $digit2 = ($sum2 * 10) % 11;
    $digit2 = ($digit2 == 10) ? 0 : $digit2;
    
    return ($cpf[9] == $digit1 && $cpf[10] == $digit2);
}

function formatCPF($cpf) {
    return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
}

function uploadFoto($file) {
    $targetDir = 'uploads/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileName = uniqid() . '_' . basename($file['name']);
    $targetFilePath = $targetDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        return $fileName;
    }
    
    return null;
}

// Processamento do Formulário
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação e processamento do formulário
    $requiredFields = ['nome', 'cpf', 'email', 'telefone', 'experiencia', 'horarios'];
    $isValid = true;

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $errorMessage = "O campo $field é obrigatório.";
            $isValid = false;
            break;
        }
    }

    // Validação de CPF
    if ($isValid && !validateCPF($_POST['cpf'])) {
        $errorMessage = 'CPF inválido.';
        $isValid = false;
    }

    // Processamento de foto
    $fotoFileName = null;
    if (!empty($_FILES['foto']['name'])) {
        $fotoFileName = uploadFoto($_FILES['foto']);
    }

    // Processamento de especialidades
    $validEspecialidades = ['corte', 'barba', 'quimica', 'pigmentacao'];
    $especialidades = isset($_POST['especialidades']) ? array_intersect($_POST['especialidades'], $validEspecialidades) : [];

    // Inserção no banco de dados
   // Inserção no banco de dados
if ($isValid) {
    try {
        $sql = "INSERT INTO barbeiros (nome, cpf, email, telefone, experiencia, horarios, sobre, foto) 
                VALUES (:nome, :cpf, :email, :telefone, :experiencia, :horarios, :sobre, :foto)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $_POST['nome'],
            ':cpf' => formatCPF($_POST['cpf']),
            ':email' => $_POST['email'],
            ':telefone' => $_POST['telefone'],
            ':experiencia' => $_POST['experiencia'],
            ':horarios' => $_POST['horarios'],
            ':sobre' => $_POST['sobre'] ?? null,
            ':foto' => $fotoFileName
        ]);

        // Inserção de especialidades
        $barbeiro_id = $pdo->lastInsertId();
        if (!empty($especialidades)) {
            $especialidadesSql = "INSERT INTO barbeiro_especialidades (barbeiro_id, especialidade) VALUES (:barbeiro_id, :especialidade)";
            $especialidadesStmt = $pdo->prepare($especialidadesSql);
            
            foreach ($especialidades as $especialidade) {
                $especialidadesStmt->execute([
                    ':barbeiro_id' => $barbeiro_id,
                    ':especialidade' => $especialidade
                ]);
            }
        }

        // Redirect to login page after successful registration
        header("Location: telaLogin.php?registro=sucesso");
        exit(); // Ensure no further script execution after redirect
    } catch(PDOException $e) {
        $errorMessage = 'Erro ao cadastrar barbeiro: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/styleBarbeiro.css">
    <title>Cadastro de Barbeiro</title>
   
</head>
<body>
    <div class="cadastro-container">
        <h1>Cadastro de Barbeiro</h1>
        
        <?php if ($successMessage): ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        
        <?php if ($errorMessage): ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="foto-preview" onclick="document.getElementById('foto').click()">
                <span id="fotoPlaceholder">Clique para adicionar foto</span>
                <img id="fotoPreview" src="#" alt="Prévia da foto" style="display:none;">
            </div>
            <input type="file" id="foto" name="foto" accept="image/*" style="display: none;">
            
            <div class="form-grid">
                <div class="input-group">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" id="nome" name="nome" required value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>">
                </div>
                
                <div class="input-group">
                    <label for="cpf">CPF *</label>
                    <input type="text" id="cpf" name="cpf" required maxlength="14" value="<?php echo isset($_POST['cpf']) ? htmlspecialchars($_POST['cpf']) : ''; ?>">
                </div>

                <div class="input-group">
                    <label for="email">E-mail *</label>
                    <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div class="input-group">
                    <label for="telefone">Telefone *</label>
                    <input type="tel" id="telefone" name="telefone" required value="<?php echo isset($_POST['telefone']) ? htmlspecialchars($_POST['telefone']) : ''; ?>">
                </div>

               

                <div class="input-group">
                    <label for="experiencia">Anos de Experiência *</label>
                    <input type="number" id="experiencia" name="experiencia" min="0" required value="<?php echo isset($_POST['experiencia']) ? htmlspecialchars($_POST['experiencia']) : ''; ?>">
                </div>

                <div class="input-group full-width">
                    <label>Especialidades *</label>
                    <div class="especialidades-grid">
                        <div class="checkbox-group">
                            <input type="checkbox" id="corte" name="especialidades[]" value="corte" 
                                <?php echo (isset($_POST['especialidades']) && in_array('corte', $_POST['especialidades'])) ? 'checked' : ''; ?>>
                            <label for="corte">Corte Masculino</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="barba" name="especialidades[]" value="barba"
                                <?php echo (isset($_POST['especialidades']) && in_array('barba', $_POST['especialidades'])) ? 'checked' : ''; ?>>
                            <label for="barba">Barba</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="quimica" name="especialidades[]" value="quimica"
                                <?php echo (isset($_POST['especialidades']) && in_array('quimica', $_POST['especialidades'])) ? 'checked' : ''; ?>>
                            <label for="quimica">Química</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="pigmentacao" name="especialidades[]" value="pigmentacao"
                                <?php echo (isset($_POST['especialidades']) && in_array('pigmentacao', $_POST['especialidades'])) ? 'checked' : ''; ?>>
                            <label for="pigmentacao">Pigmentação</label>
                        </div>
                    </div>
                </div>

                <div class="input-group full-width">
                    <label for="horarios">Horários de Trabalho *</label>
                    <select id="horarios" name="horarios" required>
                        <option value="">Selecione o horário</option>
                        <option value="integral" <?php echo (isset($_POST['horarios']) && $_POST['horarios'] == 'integral') ? 'selected' : ''; ?>>Integral (9h às 18h)</option>
                        <option value="manha" <?php echo (isset($_POST['horarios']) && $_POST['horarios'] == 'manha') ? 'selected' : ''; ?>>Manhã (9h às 15h)</option>
                        <option value="tarde" <?php echo (isset($_POST['horarios']) && $_POST['horarios'] == 'tarde') ? 'selected' : ''; ?>>Tarde (15h às 22h)</option>
                        <option value="flexivel" <?php echo (isset($_POST['horarios']) && $_POST['horarios'] == 'flexivel') ? 'selected' : ''; ?>>Horário Flexível</option>
                    </select>
                </div>

                <div class="input-group full-width">
                    <label for="sobre">Sobre você</label>
                    <textarea id="sobre" name="sobre" rows="4" placeholder="Conte um pouco sobre sua experiência e estilo de trabalho..."><?php echo isset($_POST['sobre']) ? htmlspecialchars($_POST['sobre']) : ''; ?></textarea>
                </div>
            </div>
            
            <button type="submit">Cadastrar</button>
        </form>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Máscara para CPF
        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            e.target.value = value;
        });

        // Máscara para telefone
        document.getElementById('telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            
            if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            } else {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            }
            
            e.target.value = value;
        });

        // Pré-visualização de foto
        const fotoInput = document.getElementById('foto');
        const fotoPreview = document.getElementById('fotoPreview');
        const fotoPlaceholder = document.getElementById('fotoPlaceholder');

        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    fotoPreview.src = e.target.result;
                    fotoPreview.style.display = 'block';
                    fotoPlaceholder.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    });
    </script>
</body>
</html>