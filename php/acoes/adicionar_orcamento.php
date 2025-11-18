<?php
// 1. INICIAR SESSÃO E CONEXÃO
session_start();
require_once '../conexao.php';

// Função auxiliar para responder
function responder($sucesso, $mensagem, $dados_extras = [], $is_ajax) {
    if ($is_ajax) {
        header('Content-Type: application/json');
        $resposta = array_merge(['sucesso' => $sucesso, 'mensagem' => $mensagem], $dados_extras);
        echo json_encode($resposta);
        exit();
    } else {
        // Se sucesso, redireciona com msg de sucesso
        if ($sucesso) {
            header("Location: ../orcamento.php?sucesso=add");
        } else {
            // Se erro, redireciona com msg de erro (você pode tratar isso no front depois)
            header("Location: ../produtos.php?erro=" . urlencode($mensagem));
        }
        exit();
    }
}

$is_ajax = isset($_POST['ajax']) && $_POST['ajax'] == '1';

// 2. SEGURANÇA
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode(['sucesso' => false, 'erro' => 'login_necessario']);
        exit();
    } else {
        header("Location: ../login.php?erro=restrito");
        exit();
    }
}

// 3. PROCESSAMENTO
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_produto'])) {

    $id_usuario = $_SESSION['id_usuario'];
    $id_produto = (int)$_POST['id_produto'];
    // Pega a quantidade, se não vier, assume 1
    $qtd_add = (int)($_POST['quantidade'] ?? 1);

    // ***** VALIDAÇÃO: Se quantidade for menor que 1, PARE *****
    if ($qtd_add < 1) {
        responder(false, "A quantidade mínima é 1.", [], $is_ajax);
        exit(); // Garante que o script pare aqui
    }

    try {
        // --- PASSO 1: INSERIR OU ATUALIZAR ---
        
        // Verifica se já existe
        $stmt = $conn->prepare("SELECT quantidade FROM orcamento_itens WHERE id_usuario = ? AND id_produto = ?");
        $stmt->bind_param("ii", $id_usuario, $id_produto);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            // UPDATE
            $row = $res->fetch_assoc();
            $nova_qtd_bd = $row['quantidade'] + $qtd_add;
            
            $upd = $conn->prepare("UPDATE orcamento_itens SET quantidade = ? WHERE id_usuario = ? AND id_produto = ?");
            $upd->bind_param("iii", $nova_qtd_bd, $id_usuario, $id_produto);
            $upd->execute();
            $upd->close();
        } else {
            // INSERT
            $ins = $conn->prepare("INSERT INTO orcamento_itens (id_usuario, id_produto, quantidade) VALUES (?, ?, ?)");
            $ins->bind_param("iii", $id_usuario, $id_produto, $qtd_add);
            $ins->execute();
            $ins->close();
        }
        $stmt->close();

        // --- PASSO 2: CALCULAR DADOS PARA O FRONTEND ---

        // A. Quantidade total deste produto específico (para atualizar o botão do card)
        $q_esp = $conn->prepare("SELECT quantidade FROM orcamento_itens WHERE id_usuario = ? AND id_produto = ?");
        $q_esp->bind_param("ii", $id_usuario, $id_produto);
        $q_esp->execute();
        $res_esp = $q_esp->get_result();
        $row_esp = $res_esp->fetch_assoc();
        $qtd_especifica = $row_esp['quantidade'] ?? 0;
        $q_esp->close();

        // B. Quantidade total do carrinho (para atualizar o cabeçalho)
        $q_tot = $conn->prepare("SELECT COUNT(id_produto) as total FROM orcamento_itens WHERE id_usuario = ?");
        $q_tot->bind_param("i", $id_usuario);
        $q_tot->execute();
        $res_tot = $q_tot->get_result();
        $row_tot = $res_tot->fetch_assoc();
        $qtd_total_carrinho = $row_tot['total'] ?? 0;
        $q_tot->close();

        // --- PASSO 3: RESPONDER ---
        responder(true, "Adicionado!", [
            'nova_qtd_produto' => $qtd_especifica,   // Atualiza o card
            'nova_qtd_total'   => $qtd_total_carrinho // Atualiza o cabeçalho
        ], $is_ajax);

    } catch (Exception $e) {
        if ($is_ajax) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no servidor']);
            exit();
        } else {
            header("Location: ../produtos.php?erro=bd");
            exit();
        }
    }
} else {
    header("Location: ../produtos.php");
    exit();
}
?>