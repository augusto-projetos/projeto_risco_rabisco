<?php
// Sempre iniciar a sessão no topo
session_start();

// Incluir o arquivo de conexão
require_once 'conexao.php';

// Inicializar variáveis
$mensagem_erro = "";
$nome = "";
$email = "";

// Verificar se o formulário foi enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Coletar e limpar os dados
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];

    // ***** NOVO: Validação de Senha Forte *****
    $tem_maiuscula = preg_match('@[A-Z]@', $senha);
    $tem_minuscula = preg_match('@[a-z]@', $senha);
    $tem_numero    = preg_match('@[0-9]@', $senha);
    $tem_especial  = preg_match('@[^\w]@', $senha); // Qualquer coisa que não seja letra ou número

    // 2. Validar os dados
    if (empty($nome) || empty($email) || empty($senha) || empty($confirma_senha)) {
        $mensagem_erro = "Todos os campos são obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem_erro = "Por favor, insira um e-mail válido.";
    } elseif (strlen($senha) < 8 || !$tem_maiuscula || !$tem_minuscula || !$tem_especial) {
        // Se falhar em qualquer critério
        $mensagem_erro = "A senha deve ter pelo menos 8 caracteres, uma maiúscula, uma minúscula e um caractere especial.";
    } elseif ($senha !== $confirma_senha) {
        $mensagem_erro = "As senhas não coincidem.";
    } else {
        
        // 3. Verificar se email já existe
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $mensagem_erro = "Este e-mail já está cadastrado. Tente fazer login.";
        } else {
            // 4. Criar a conta
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            $stmt_insert = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $nome, $email, $senha_hash);

            if ($stmt_insert->execute()) {
                header("Location: login.php?sucesso=1");
                exit();
            } else {
                $mensagem_erro = "Ocorreu um erro ao criar sua conta. Tente novamente.";
            }
            $stmt_insert->close();
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Risco e Rabisco</title>
    <link rel="website icon" type="png" href="../img/rabisco.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>

    <div class="auth-container">
        <a href="../index.php" class="logo-link">
            <img src="../img/logotipo.jpg" alt="Logo Risco e Rabisco">
            <span>Risco e Rabisco</span>
        </a>

        <form class="auth-form" method="POST" action="registrar.php">
            <h2>Crie sua Conta</h2>
            <p>É rápido e fácil para começar a montar seus orçamentos.</p>

            <?php if (!empty($mensagem_erro)): ?>
                <div class="mensagem-erro">
                    <?php echo $mensagem_erro; ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="input-senha" class="senha-verifica" name="senha" required>
                
                <!-- Lista de Requisitos Visual -->
                <div class="requisitos-senha" id="box-requisitos">
                    <div class="requisito-item" id="req-tamanho">
                        <i class="fa-solid fa-circle-xmark"></i> Mínimo 8 caracteres
                    </div>
                    <div class="requisito-item" id="req-maiuscula">
                        <i class="fa-solid fa-circle-xmark"></i> Letra Maiúscula
                    </div>
                    <div class="requisito-item" id="req-minuscula">
                        <i class="fa-solid fa-circle-xmark"></i> Letra Minúscula
                    </div>
                    <div class="requisito-item" id="req-especial">
                        <i class="fa-solid fa-circle-xmark"></i> Caractere Especial (!@#$...)
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="confirma_senha">Confirmar Senha</label>
                <input type="password" id="confirma_senha" name="confirma_senha" required>
            </div>
            
            <button type="submit" class="btn-auth">Cadastrar</button>
            
            <p class="auth-link">
                Já tem uma conta? <a href="login.php">Faça Login</a>
            </p>
        </form>
    </div>

    <script src="../js/registrar.js"></script>

</body>
</html>