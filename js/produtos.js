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