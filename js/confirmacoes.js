document.addEventListener('DOMContentLoaded', () => {

    // 1. OUVIR FORMULÁRIOS DE REMOÇÃO (Lixeiras)
    // Seleciona todos os formulários da página
    const formularios = document.querySelectorAll('form');

    formularios.forEach(form => {
        form.addEventListener('submit', (e) => {
            
            // Verifica se é uma remoção de FAVORITO
            const inputAcao = form.querySelector('input[name="acao"]');
            if (inputAcao && inputAcao.value === 'remover') {
                if (!confirm("Tem certeza que deseja remover este item dos seus favoritos?")) {
                    e.preventDefault(); // Cancela o envio
                }
                return;
            }

            // Verifica se é uma remoção de ORÇAMENTO (Botão Lixeira - Quantidade 0)
            const inputQtd = form.querySelector('input[name="quantidade"]');
            if (inputQtd && inputQtd.value == '0') { // Usamos == para pegar '0' string ou número
                 if (!confirm("Tem certeza que deseja remover este item do seu orçamento?")) {
                    e.preventDefault(); // Cancela o envio
                }
                return;
            }
            
            // Verifica se é uma ATUALIZAÇÃO DE ORÇAMENTO com valor 0 ou negativo
            // (Caso o usuário digite 0 manualmente e clique em atualizar)
            // O inputQtd já foi pego acima. Se ele existe e o valor for <= 0
             if (inputQtd && parseInt(inputQtd.value) <= 0) {
                 if (!confirm("Definir a quantidade como 0 ou menos removerá o item. Deseja continuar?")) {
                    e.preventDefault(); // Cancela o envio
                }
                return;
            }

        });
    });

});