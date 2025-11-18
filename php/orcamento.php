<?php
// 1. Definir o CSS específico desta página
$pagina_css = "../css/orcamento.css";

// 2. Incluir o cabeçalho
include 'cabecalho.php';

// 3. Buscar os itens do orçamento
$total_geral = 0;
$itens_orcamento = [];

$sql = "SELECT 
            p.id, 
            p.nome, 
            p.preco, 
            p.imagem, 
            oi.quantidade 
        FROM 
            orcamento_itens AS oi
        JOIN 
            produtos AS p ON oi.id_produto = p.id
        WHERE 
            oi.id_usuario = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario_logado);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    while ($item = $resultado->fetch_assoc()) {
        $itens_orcamento[] = $item;
    }
}
$stmt->close();
$conn->close();
?>

<h1 class="titulo-pagina">Meu Orçamento</h1>

<div class="orcamento-container">

    <?php if (empty($itens_orcamento)): ?>
        
        <div class="orcamento-vazio">
            <i class="fa-solid fa-file-invoice-dollar"></i>
            <h2>Seu orçamento está vazio!</h2>
            <p>Adicione produtos do catálogo para ver o cálculo aqui.</p>
            <a href="produtos.php" class="btn-primario">Ver Produtos</a>
        </div>

    <?php else: ?>

        <div class="orcamento-cheio">
            
            <div class="tabela-itens">
                <div class="linha-item cabecalho-tabela">
                    <div class="item-produto">Produto</div>
                    <div class="item-preco">Preço Unit.</div>
                    <div class="item-qtd">Quantidade</div>
                    <div class="item-subtotal">Subtotal</div>
                    <div class="item-acao">Ação</div>
                </div>

                <?php foreach ($itens_orcamento as $item): 
                    $subtotal_item = $item['preco'] * $item['quantidade'];
                    $total_geral += $subtotal_item;
                ?>
                    <div class="linha-item">
                        <div class="item-produto">
                            <img src="<?php echo htmlspecialchars($item['imagem']); ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>">
                            <span><?php echo htmlspecialchars($item['nome']); ?></span>
                        </div>
                        
                        <div class="item-preco">
                            <?php echo "R$ " . number_format($item['preco'], 2, ',', '.'); ?>
                        </div>
                        
                        <div class="item-qtd">
                            <form action="acoes/atualizar_orcamento.php" method="POST" class="form-atualizar-qtd">
                                <input type="hidden" name="id_produto" value="<?php echo $item['id']; ?>">
                                <input type="number" name="quantidade" value="<?php echo $item['quantidade']; ?>" min="0" max="99" class="input-quantidade">
                                
                                <!-- Tooltip no botão Atualizar -->
                                <button type="submit" class="btn-atualizar tooltip" data-tooltip="Atualizar Quantidade">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                        </div>

                        <div class="item-subtotal">
                            <?php echo "R$ " . number_format($subtotal_item, 2, ',', '.'); ?>
                        </div>

                        <div class="item-acao">
                            <form action="acoes/atualizar_orcamento.php" method="POST"> 
                                <input type="hidden" name="id_produto" value="<?php echo $item['id']; ?>">
                                <input type="hidden" name="quantidade" value="0">
                                
                                <!-- Tooltip no botão Remover -->
                                <button type="submit" class="btn-remover tooltip" data-tooltip="Remover Item">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div> 

            <div class="resumo-pedido">
                <h3>Resumo do Orçamento</h3>
                <div class="resumo-linha">
                    <span>Total dos Produtos</span>
                    <span class="preco-total"><?php echo "R$ " . number_format($total_geral, 2, ',', '.'); ?></span>
                </div>
                <p class="aviso-frete">
                    Este é um orçamento preliminar. O valor não inclui frete ou taxas adicionais.
                </p>
                <button class="btn-primario btn-finalizar" disabled>
                    Solicitar Orçamento (Em breve)
                </button>
            </div>

        </div> 

    <?php endif; ?>

</div>

<script src="../js/confirmacoes.js"></script>

<?php
// Fechamento do HTML
?>
</main>
</body>
</html>