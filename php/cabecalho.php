<?php
// 1. INICIAR SESSÃO E CONEXÃO
// Sempre iniciar a sessão primeiro
session_start();

// Incluir o arquivo de conexão
require_once 'conexao.php';

// 2. SEGURANÇA (O "PORTEIRO")
// Verificar se o usuário NÃO está logado
// Se 'logado' não existir ou for 'false', expulsa o usuário
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    
    // Limpa qualquer lixo da sessão
    session_unset();
    session_destroy();

    // Redireciona para o login com uma mensagem de erro
    // (O 'erro=restrito' será lido pelo login.php)
    header("Location: login.php?erro=restrito");
    exit(); // Sempre use 'exit()' após um 'header'
}

// 3. DADOS DA SESSÃO
// Se o usuário está logado, pegamos os dados dele para usar no HTML
$id_usuario_logado = $_SESSION['id_usuario'];
$nome_usuario_logado = $_SESSION['nome_usuario']; // Ex: "Carlos"
$primeiro_nome = explode(' ', $nome_usuario_logado)[0]; // Pega só "Carlos"

// 4. CALCULAR TOTAL DE PRODUTOS NO ORÇAMENTO
$total_produtos = 0;
if (isset($conn)) {
    // Usamos COUNT(id_produto) para contar o total de produtos
    $sql_contagem = "SELECT COUNT(id_produto) as total FROM orcamento_itens WHERE id_usuario = ?";
    $stmt_contagem = $conn->prepare($sql_contagem);
    if ($stmt_contagem) {
        $stmt_contagem->bind_param("i", $id_usuario_logado);
        $stmt_contagem->execute();
        $res_contagem = $stmt_contagem->get_result();
        if ($row_contagem = $res_contagem->fetch_assoc()) {
            // Se retornar null (sem itens), define como 0
            $total_produtos = $row_contagem['total'] ?? 0;
        }
        $stmt_contagem->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <link rel="website icon" type="png" href="../img/rabisco.png">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" href="../css/cabecalho.css">
        
        <?php if (isset($pagina_css)): ?>
            <link rel="stylesheet" href="<?php echo $pagina_css; ?>">
        <?php endif; ?>

        <script defer src="../js/menu.js"></script>
        
    </head>
    <body>

        <a href="#" id="btn-voltar-ao-topo" class="btn-voltar-ao-topo" aria-label="Voltar ao topo">
            <i class="fa-solid fa-arrow-up"></i>
        </a>
        
        <header class="cabecalho-container">
            <a href="produtos.php" class="logo-link">
                <img src="../img/logotipo.jpg" alt="Logo Risco e Rabisco">
                <span>Risco e Rabisco</span>
            </a>

            <div class="container-nav" id="container-nav">
                
                <nav class="navbar">
                    <a href="produtos.php">
                        <i class="fa-solid fa-store"></i> Produtos
                    </a>
                    <a href="favoritos.php">
                        <i class="fa-solid fa-heart"></i> Favoritos
                    </a>
                    <a href="orcamento.php">
                        <i class="fa-solid fa-file-invoice-dollar"></i> Orçamento
                        
                        <?php if ($total_produtos > 0): ?>
                            <span id="contador-orcamento" class="badge-contador"><?php echo $total_produtos; ?></span>
                        <?php else: ?>
                            <span id="contador-orcamento" class="badge-contador" style="display: none;">0</span>
                        <?php endif; ?>
                    </a>
                </nav>

                <div class="menu-usuario">
                    <div class="dropdown">
                        <button class="dropdown-toggle">
                            <i class="fa-solid fa-user-circle"></i>
                            Olá, <?php echo htmlspecialchars($primeiro_nome); ?>
                            <i class="fa-solid fa-caret-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="perfil.php">
                                <i class="fa-solid fa-user-pen"></i> Meu Perfil
                            </a>
                            <a href="logout.php" class="link-sair">
                                <i class="fa-solid fa-right-from-bracket"></i> Sair
                            </a>
                        </div>
                    </div>
                </div>

            </div> <button class="btn-mobile" id="btn-mobile" aria-label="Abrir Menu" aria-expanded="false" aria-controls="container-nav">
                <i class="fa-solid fa-bars"></i>
            </button>

        </header>

        <main class="conteudo-principal">