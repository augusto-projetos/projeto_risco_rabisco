<?php
// 1. Definir o CSS específico desta página
// Esta variável será lida pelo 'cabecalho.php'
$pagina_css = "../css/favoritos.css";

// 2. Incluir o cabeçalho
// O 'cabecalho.php' já faz 3 coisas:
// 1. Inicia a sessão (session_start())
// 2. Inclui a conexão (require_once 'conexao.php')
// 3. Verifica se o usuário está logado (protege a página)
include 'cabecalho.php';

// 3. Pré-buscar o Orçamento (para status do botão)
// A ID $id_usuario_logado já foi definida no cabeçalho
$carrinho_set = [];
$sql_carrinho = "SELECT id_produto, quantidade FROM orcamento_itens WHERE id_usuario = ?";
$stmt_carrinho = $conn->prepare($sql_carrinho);
$stmt_carrinho->bind_param("i", $id_usuario_logado);
$stmt_carrinho->execute();
$result_carrinho = $stmt_carrinho->get_result();
while ($row_carrinho = $result_carrinho->fetch_assoc()) {
    $carrinho_set[$row_carrinho['id_produto']] = $row_carrinho['quantidade'];
}
$stmt_carrinho->close();


// 4. Buscar os Itens Favoritos do usuário
$favoritos = []; // Array para guardar os produtos

// Usamos um JOIN para buscar os dados dos produtos
// que estão na tabela de favoritos deste usuário
$sql_fav = "SELECT 
                p.id, 
                p.nome, 
                p.descricao, 
                p.preco, 
                p.imagem 
            FROM 
                favoritos AS f
            JOIN 
                produtos AS p ON f.id_produto = p.id
            WHERE 
                f.id_usuario = ?";

$stmt_fav = $conn->prepare($sql_fav);
$stmt_fav->bind_param("i", $id_usuario_logado);
$stmt_fav->execute();
$resultado_fav = $stmt_fav->get_result();

// Coloca todos os resultados em um array
if ($resultado_fav->num_rows > 0) {
    while ($item = $resultado_fav->fetch_assoc()) {
        $favoritos[] = $item;
    }
}
$stmt_fav->close();
$conn->close(); // Fechamos a conexão, já temos todos os dados
?>

<h1 class="titulo-pagina">Meus Favoritos</h1>

<?php if (empty($favoritos)): ?>
    
    <div class="lista-vazia">
        <i class="fa-regular fa-heart"></i>
        <h2>Sua lista de favoritos está vazia!</h2>
        <p>Clique no ícone <i class="fa-regular fa-heart"></i> nos produtos para salvá-los aqui.</p>
        <a href="produtos.php" class="btn-primario">Ver Catálogo</a>
    </div>

<?php else: ?>

    <div class="container-produtos">
        
        <?php foreach ($favoritos as $produto): 
            // Prepara variáveis
            $id_produto = $produto['id'];
            $nome_produto = htmlspecialchars($produto['nome']);
            $descricao_produto = htmlspecialchars($produto['descricao']);
            $preco_produto = "R$ " . number_format($produto['preco'], 2, ',', '.');
            $imagem_produto = htmlspecialchars($produto['imagem']);

            // Verifica o status do carrinho
            $no_carrinho_qtd = $carrinho_set[$id_produto] ?? 0;
        ?>

            <div class="card-produto">
                
                <div class="card-produto-imagem">
                    <img src="<?php echo $imagem_produto; ?>" alt="<?php echo $nome_produto; ?>">
                </div>

                <div class="card-produto-info">
                    <h3><?php echo $nome_produto; ?></h3>
                    
                    <?php if (!empty($descricao_produto)): ?>
                        <p class="descricao"><?php echo $descricao_produto; ?></p>
                    <?php endif; ?>

                    <div class="preco"><?php echo $preco_produto; ?></div>
                </div>

                <div class="card-produto-acoes">
                    
                    <form action="acoes/adicionar_favorito.php" method="POST">
                        <input type="hidden" name="id_produto" value="<?php echo $id_produto; ?>">
                        <input type="hidden" name="acao" value="remover"> <button type="submit" class="btn-remover-favorito">
                            <i class="fa-solid fa-trash-can"></i> Remover
                        </button>
                    </form>

                    <form action="acoes/adicionar_orcamento.php" method="POST" class="form-orcamento">
                        <input type="hidden" name="id_produto" value="<?php echo $id_produto; ?>">
                        
                        <input type="number" name="quantidade" value="1" min="1" max="99" class="input-quantidade">
                        
                        <button type="submit" class="btn-orcamento">
                            <?php if ($no_carrinho_qtd > 0): ?>
                                <i class="fa-solid fa-cart-plus"></i> (<?php echo $no_carrinho_qtd; ?>)
                            <?php else: ?>
                                <i class="fa-solid fa-cart-shopping"></i> Orçar
                            <?php endif; ?>
                        </button>
                    </form>

                </div> </div> <?php endforeach; ?>

    </div> <?php endif; ?> <?php
// 7. Fechamento do HTML
?>

<script src="../js/confirmacoes.js"></script>

</main>
</body>
</html>