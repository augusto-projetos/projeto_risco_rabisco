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