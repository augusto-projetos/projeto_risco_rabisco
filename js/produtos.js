// Espera o HTML carregar
document.addEventListener('DOMContentLoaded', () => {
    
    // Pega os elementos
    const searchBar = document.getElementById('search-bar');
    const containerProdutos = document.querySelector('.container-produtos');
    
    // CORREÇÃO: Procuramos por '.card-produto', não '.produto'
    const produtos = containerProdutos.querySelectorAll('.card-produto'); 
    
    const mensagemNenhumProduto = document.getElementById('nenhum-produto-encontrado');

    // Adiciona o evento de "digitar"
    searchBar.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        let produtosVisiveis = 0;

        produtos.forEach(produto => {
            // CORREÇÃO: Procuramos por 'h3', não 'h2'
            const titulo = produto.querySelector('.card-produto-info h3').textContent.toLowerCase();
            
            if (titulo.includes(searchTerm)) {
                // Usamos 'flex' pois o card é display: flex
                produto.style.display = 'flex'; 
                produtosVisiveis++;
            } else {
                produto.style.display = 'none';
            }
        });

        // Mostra a mensagem de "nenhum produto" se necessário
        if (produtosVisiveis === 0) {
            mensagemNenhumProduto.style.display = 'block';
        } else {
            mensagemNenhumProduto.style.display = 'none';
        }
    });
});

// Script AJAX para Favoritos
async function alternarFavorito(botao) {
    const idProduto = botao.getAttribute('data-id');
    const icone = botao.querySelector('i');
    const textoSpan = botao.querySelector('.texto-favorito');
    
    // Feedback Visual Imediato (Otimista)
    // Se tem a classe 'ativo', estamos removendo. Se não, adicionando.
    const estaAtivo = botao.classList.contains('ativo');
    
    if (estaAtivo) {
        // Removendo
        botao.classList.remove('ativo');
        icone.classList.remove('fa-solid');
        icone.classList.add('fa-regular');
        if(textoSpan) textoSpan.textContent = 'Favoritar';
        // Remove o atributo disabled se estiver usando
        botao.disabled = false; 
    } else {
        // Adicionando
        botao.classList.add('ativo');
        icone.classList.remove('fa-regular');
        icone.classList.add('fa-solid');
        if(textoSpan) textoSpan.textContent = 'Favoritado';
        botao.disabled = false; 
    }

    try {
        const response = await fetch('acoes/adicionar_favorito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id_produto=${idProduto}&ajax=1`
        });
        
        const data = await response.json();
        
        if (!data.sucesso) {
            // Se deu erro, reverte o visual
            alert('Erro: ' + data.mensagem);
            // Reverte a lógica visual acima
            if (estaAtivo) {
                botao.classList.add('ativo');
                icone.classList.add('fa-solid');
                icone.classList.remove('fa-regular');
                if(textoSpan) textoSpan.textContent = 'Já Favoritado';
            } else {
                botao.classList.remove('ativo');
                icone.classList.add('fa-regular');
                icone.classList.remove('fa-solid');
                if(textoSpan) textoSpan.textContent = 'Favoritar';
            }
        }

    } catch (error) {
        console.error('Erro:', error);
        // Reverte em caso de erro de rede
    }
}

// Script AJAX para Orçamento
async function adicionarAoOrcamento(botao) {
    const idProduto = botao.getAttribute('data-id');
    // Acha o input irmão dentro da div
    const inputQtd = botao.parentElement.querySelector('.input-quantidade');
    const quantidade = inputQtd.value;
    const spanConteudo = botao.querySelector('.conteudo-btn');

    // Feedback visual (Loading)
    const textoOriginal = spanConteudo.innerHTML;
    spanConteudo.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ...';
    botao.disabled = true;

    try {
        const response = await fetch('acoes/adicionar_orcamento.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id_produto=${idProduto}&quantidade=${quantidade}&ajax=1`
        });
        
        const data = await response.json();
        
        if (data.sucesso) {
            // Atualiza o contador no cabeçalho (Se o PHP enviou nova_qtd_total)
            const contador = document.getElementById('contador-orcamento');
            if (contador && data.nova_qtd_total !== undefined) {
                contador.textContent = data.nova_qtd_total;
                contador.style.display = 'inline-flex';
            }

            // Atualiza o botão deste card com a quantidade específica
            if (data.nova_qtd_produto !== undefined) {
                    spanConteudo.innerHTML = `<i class="fa-solid fa-cart-plus"></i> Adicionar + (${data.nova_qtd_produto})`;
            } else {
                // Fallback se não vier a quantidade
                spanConteudo.innerHTML = '<i class="fa-solid fa-check"></i> Adicionado!';
                setTimeout(() => {
                    spanConteudo.innerHTML = textoOriginal; // Volta ao original
                }, 2000);
            }
            
            botao.disabled = false;

        } else {
            alert('Erro: ' + (data.mensagem || data.erro));
            spanConteudo.innerHTML = textoOriginal;
            botao.disabled = false;
        }

    } catch (error) {
        console.error('Erro:', error);
        alert('Erro de conexão.');
        spanConteudo.innerHTML = textoOriginal;
        botao.disabled = false;
    }
}