<?php
// 1. INICIAR SESSÃO E CONEXÃO
session_start();
require_once '../conexao.php';

// Função auxiliar para responder (seja JSON ou Redirecionamento)
function responder($sucesso, $mensagem, $redirect_url, $is_ajax) {
    if ($is_ajax) {
        // Se for AJAX, retorna JSON e para o script
        header('Content-Type: application/json');
        echo json_encode(['sucesso' => $sucesso, 'mensagem' => $mensagem]);
        exit();
    } else {
        // Se for formulário normal, redireciona
        header("Location: " . $redirect_url);
        exit();
    }
}

// Verifica se a requisição é AJAX (pelo parâmetro POST 'ajax' ou cabeçalho)
$is_ajax = isset($_POST['ajax']) && $_POST['ajax'] == '1';

// 2. SEGURANÇA (O "PORTEIRO")
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    if ($is_ajax) {
        responder(false, "Você precisa estar logado.", "", true);
    } else {
        header("Location: ../login.php?erro=restrito");
        exit();
    }
}

// 3. VERIFICAR SE O MÉTODO É POST E SE O ID VEIO
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_produto'])) {

    // 4. COLETAR DADOS
    $id_usuario = $_SESSION['id_usuario'];
    $id_produto = (int)$_POST['id_produto'];
    $acao = $_POST['acao'] ?? 'adicionar';

    // 5. LÓGICA DE ADICIONAR/REMOVER
    try {
        if ($acao == 'remover') {
            // --- REMOÇÃO ---
            $stmt_exec = $conn->prepare("DELETE FROM favoritos WHERE id_usuario = ? AND id_produto = ?");
            $stmt_exec->bind_param("ii", $id_usuario, $id_produto);
            $stmt_exec->execute();
            
            responder(true, "Produto removido dos favoritos.", "../favoritos.php?sucesso=removido", $is_ajax);

        } else {
            // --- ADIÇÃO (TOGGLE) ---
            // Para a Home, vamos fazer um "toggle" inteligente:
            // Se já existe, remove. Se não existe, adiciona.
            
            // Primeiro verificamos se já existe
            $check = $conn->prepare("SELECT id FROM favoritos WHERE id_usuario = ? AND id_produto = ?");
            $check->bind_param("ii", $id_usuario, $id_produto);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                // Já existe -> Removemos
                $stmt_exec = $conn->prepare("DELETE FROM favoritos WHERE id_usuario = ? AND id_produto = ?");
                $stmt_exec->bind_param("ii", $id_usuario, $id_produto);
                $stmt_exec->execute();
                $msg = "Produto removido dos favoritos.";
                $status = "removido";
            } else {
                // Não existe -> Adicionamos
                $stmt_exec = $conn->prepare("INSERT INTO favoritos (id_usuario, id_produto) VALUES (?, ?)");
                $stmt_exec->bind_param("ii", $id_usuario, $id_produto);
                $stmt_exec->execute();
                $msg = "Produto adicionado aos favoritos.";
                $status = "adicionado";
            }
            
            responder(true, $msg, "../produtos.php?sucesso=" . $status, $is_ajax);
        }

        $conn->close();

    } catch (mysqli_sql_exception $e) {
        // Tratamento de erro
        if ($e->getCode() == 1452) {
            session_unset(); session_destroy();
            if ($is_ajax) {
                responder(false, "Sessão inválida.", "", true);
            } else {
                header("Location: ../login.php?erro=sessao_invalida");
            }
        } else {
            responder(false, "Erro no banco de dados.", "../produtos.php?erro=bd", $is_ajax);
        }
    }

} else {
    header("Location: ../produtos.php");
    exit();
}
?>