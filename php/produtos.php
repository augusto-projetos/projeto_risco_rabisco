<?php
// 1. Definir o CSS específico desta página
$pagina_css = '../css/produtos.css';

// 2. Incluir o cabeçalho
include 'cabecalho.php';

// 3. Buscar os produtos no banco de dados
$sql = "SELECT id, nome, descricao, preco, imagem FROM produtos ORDER BY nome ASC";
$resultado = $conn->query($sql);

// 4. Buscar favoritos e carrinho do usuário
$id_usuario_logado = $_SESSION['id_usuario'];

// Busca IDs dos favoritos
$favoritos_set = [];
$sql_fav = "SELECT id_produto FROM favoritos WHERE id_usuario = ?";
$stmt_fav = $conn->prepare($sql_fav);
$stmt_fav->bind_param("i", $id_usuario_logado);
$stmt_fav->execute();
$result_fav = $stmt_fav->get_result();
while ($row_fav = $result_fav->fetch_assoc()) {
    $favoritos_set[$row_fav['id_produto']] = true;
}
$stmt_fav->close();

// Busca IDs e Quantidade do carrinho/orçamento
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

?>

<h1 class="titulo-pagina">Nosso Catálogo de Produtos</h1>

<div class="container-pesquisa">
    <i class="fa-solid fa-search"></i>
    <input type="text" id="search-bar" placeholder="Buscar produtos pelo nome...">
</div>

<div class="container-produtos">

    <?php
    // 5. O Loop para exibir os produtos
    if ($resultado->num_rows > 0) {
        while ($produto = $resultado->fetch_assoc()) {
            
            $id_produto = $produto['id'];
            $nome_produto = htmlspecialchars($produto['nome']);
            $descricao_produto = htmlspecialchars($produto['descricao']);
            $preco_produto = "R$ " . number_format($produto['preco'], 2, ',', '.');
            $imagem_produto = htmlspecialchars($produto['imagem']);

            // Verifica status
            $esta_favoritado = isset($favoritos_set[$id_produto]);
            $no_carrinho_qtd = $carrinho_set[$id_produto] ?? 0;
            
            // Define o ícone inicial (cheio ou vazio)
            $classe_icone = $esta_favoritado ? 'fa-solid' : 'fa-regular';
            // Define o texto do botão
            $texto_botao = $esta_favoritado ? 'Já Favoritado' : 'Favoritar';
            // Define a classe 'ativo' para o botão se já estiver favoritado
            $classe_ativo = $esta_favoritado ? 'ativo' : '';

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
                    
                    <button type="button" 
                            class="btn-favorito <?php echo $classe_ativo; ?>" 
                            data-id="<?php echo $id_produto; ?>" 
                            onclick="alternarFavorito(this)">
                        <i class="<?php echo $classe_icone; ?> fa-heart"></i> 
                        <span class="texto-favorito"><?php echo $texto_botao; ?></span>
                    </button>
                    <form action="acoes/adicionar_orcamento.php" method="POST" class="form-orcamento">
                        <input type="hidden" name="id_produto" value="<?php echo $id_produto; ?>">
                        <input type="number" name="quantidade" value="1" min="1" max="99" class="input-quantidade">
                        
                        <button type="submit" class="btn-orcamento">
                            <?php if ($no_carrinho_qtd > 0): ?>
                                <i class="fa-solid fa-cart-plus"></i> Adicionar + (<?php echo $no_carrinho_qtd; ?>)
                            <?php else: ?>
                                <i class="fa-solid fa-cart-shopping"></i> Orçar
                            <?php endif; ?>
                        </button>
                    </form>

                </div>
            </div>

            <?php
        }
    } else {
        echo "<p>Nenhum produto encontrado no catálogo.</p>";
    }
    $conn->close();
    ?>

    <p id="nenhum-produto-encontrado" class="mensagem-busca-vazia">
        Nenhum produto encontrado com esse nome.
    </p>

</div>

<script src="../js/produtos.js"></script>

<?php
// 7. Fechamento do HTML
?>
</main>
</body>
</html>