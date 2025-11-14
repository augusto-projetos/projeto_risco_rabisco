<?php
// 1. Definir o CSS específico desta página
$pagina_css = '../css/produtos.css';

// 2. Incluir o cabeçalho
// O 'cabecalho.php' já faz 3 coisas:
// 1. Inicia a sessão (session_start())
// 2. Inclui a conexão (require_once 'conexao.php')
// 3. Verifica se o usuário está logado (protege a página)
include 'cabecalho.php';

// 3. Buscar os produtos no banco de dados
// A conexão $conn já foi criada dentro do 'cabecalho.php'
$sql = "SELECT id, nome, descricao, preco, imagem FROM produtos ORDER BY nome ASC";
$resultado = $conn->query($sql);

// 4. Buscar favoritos e carrinho do usuário (Bônus de Performance)
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

<!-- Título da Página -->
<h1 class="titulo-pagina">Nosso Catálogo de Produtos</h1>

<!-- Barra de Pesquisa -->
<div class="container-pesquisa">
    <i class="fa-solid fa-search"></i>
    <!-- Usamos o ID 'search-bar' que o seu script original procurava -->
    <input type="text" id="search-bar" placeholder="Buscar produtos pelo nome...">
</div>

<!-- Container para os cards dos produtos -->
<div class="container-produtos">

    <?php
    // 5. O Loop para exibir os produtos
    if ($resultado->num_rows > 0) {
        // Itera sobre cada linha (produto) retornada do banco
        while ($produto = $resultado->fetch_assoc()) {
            
            // Prepara variáveis para o HTML
            $id_produto = $produto['id'];
            $nome_produto = htmlspecialchars($produto['nome']);
            $descricao_produto = htmlspecialchars($produto['descricao']);
            $preco_produto = "R$ " . number_format($produto['preco'], 2, ',', '.');
            $imagem_produto = htmlspecialchars($produto['imagem']);

            $esta_favoritado = isset($favoritos_set[$id_produto]);
            $no_carrinho_qtd = $carrinho_set[$id_produto] ?? 0;

            ?>

            <!-- Card Individual do Produto (HTML dinâmico) -->
            <!-- Note a classe 'card-produto' que o JS vai procurar -->
            <div class="card-produto">
                
                <!-- Imagem -->
                <div class="card-produto-imagem">
                    <img src="<?php echo $imagem_produto; ?>" alt="<?php echo $nome_produto; ?>">
                </div>

                <!-- Informações -->
                <div class="card-produto-info">
                    <!-- O 'h3' que o JS vai procurar -->
                    <h3><?php echo $nome_produto; ?></h3>
                    
                    <?php if (!empty($descricao_produto)): ?>
                        <p class="descricao"><?php echo $descricao_produto; ?></p>
                    <?php endif; ?>

                    <div class="preco"><?php echo $preco_produto; ?></div>
                </div>

                <!-- Ações (Botões) -->
                <div class="card-produto-acoes">
                    
                    <!-- Formulário de Favorito -->
                    <form action="acoes/adicionar_favorito.php" method="POST">
                        <input type="hidden" name="id_produto" value="<?php echo $id_produto; ?>">
                        
                        <?php if ($esta_favoritado): ?>
                            <button type="submit" class="btn-favorito" disabled>
                                <i class="fa-solid fa-heart"></i> Já Favoritado
                            </button>
                        <?php else: ?>
                            <button type="submit" class="btn-favorito">
                                <i class="fa-regular fa-heart"></i> Favoritar
                            </button>
                        <?php endif; ?>
                    </form>

                    <!-- Formulário de Orçamento -->
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

                </div> <!-- Fim .card-produto-acoes -->
            </div> <!-- Fim .card-produto -->

            <?php
        } // Fim do while
    } else {
        // Caso não tenha produtos cadastrados
        echo "<p>Nenhum produto encontrado no catálogo.</p>";
    }
    // 6. Fechar a conexão
    $conn->close();
    ?>

    <!-- Mensagem para quando a busca não acha nada -->
    <p id="nenhum-produto-encontrado" class="mensagem-busca-vazia">
        Nenhum produto encontrado com esse nome.
    </p>

</div> <!-- Fim .container-produtos -->

<script src="../js/pesquisa.js"></script>

<?php
// 7. Fechamento do HTML
// O </main> e </body> </html> são fechados aqui
?>
</main>
</body>
</html>