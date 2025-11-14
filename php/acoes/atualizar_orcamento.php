<?php
// 1. INICIAR SESSÃO E CONEXÃO
session_start();
require_once '../conexao.php';

// 2. SEGURANÇA (O "PORTEIRO")
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: ../login.php?erro=restrito");
    exit();
}

// 3. VERIFICAR SE OS DADOS VIERAM (POST)
// Verificamos se 'id_produto' E 'quantidade' foram enviados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_produto']) && isset($_POST['quantidade'])) {

    // 4. COLETAR E VALIDAR DADOS
    $id_usuario = $_SESSION['id_usuario'];
    $id_produto = (int)$_POST['id_produto'];
    $nova_quantidade = (int)$_POST['quantidade'];

    // 5. LÓGICA DE ATUALIZAÇÃO/REMOÇÃO
    
    try {
        
        // Prepara o SQL com base na quantidade recebida
        if ($nova_quantidade > 0 && $nova_quantidade < 100) {
            // --- SE A QTD FOR POSITIVA: ATUALIZAR (UPDATE) ---
            $stmt_exec = $conn->prepare("UPDATE orcamento_itens SET quantidade = ? WHERE id_usuario = ? AND id_produto = ?");
            $stmt_exec->bind_param("iii", $nova_quantidade, $id_usuario, $id_produto);

        } else {
            // --- SE A QTD FOR 0 OU NEGATIVA: REMOVER (DELETE) ---
            $stmt_exec = $conn->prepare("DELETE FROM orcamento_itens WHERE id_usuario = ? AND id_produto = ?");
            $stmt_exec->bind_param("ii", $id_usuario, $id_produto);
        }

        // Executa o SQL (UPDATE ou DELETE)
        $stmt_exec->execute();
        $stmt_exec->close();
        $conn->close();

        // 6. REDIRECIONAR DE VOLTA (SUCESSO)
        header("Location: ../orcamento.php?sucesso=update");
        exit();

    } catch (mysqli_sql_exception $e) {
        // 7. "CAPTURAR" ERRO (Sessão inválida ou outro)
        if ($e->getCode() == 1452) { // Erro de Foreign Key
            session_unset();
            session_destroy();
            header("Location: ../login.php?erro=sessao_invalida");
            exit();
        } else {
            header("Location: ../orcamento.php?erro=bd");
            exit();
        }
    }

} else {
    // Se alguém acessar o arquivo sem dados POST
    header("Location: ../orcamento.php");
    exit();
}
?>