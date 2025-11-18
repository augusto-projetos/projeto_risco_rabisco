// Script de Validação Visual
const inputSenha = document.getElementsByClassName('senha-verifica')[0];
const boxRequisitos = document.getElementById('box-requisitos');

const reqTamanho = document.getElementById('req-tamanho');
const reqMaiuscula = document.getElementById('req-maiuscula');
const reqMinuscula = document.getElementById('req-minuscula');
const reqEspecial = document.getElementById('req-especial');

// Mostra a caixa quando foca
inputSenha.addEventListener('focus', () => {
    boxRequisitos.classList.add('mostrar-requisitos');
});

// Esconde a caixa quando sai do foco (clica fora)
inputSenha.addEventListener('blur', () => {
    boxRequisitos.classList.remove('mostrar-requisitos');
});

// Validação em tempo real
inputSenha.addEventListener('input', () => {
    const valor = inputSenha.value;

    // Função auxiliar para atualizar o item
    function atualizarItem(elemento, valido) {
        const icone = elemento.querySelector('i');
        if (valido) {
            elemento.classList.add('valido');
            elemento.classList.remove('invalido');
            icone.className = 'fa-solid fa-circle-check';
        } else {
            elemento.classList.remove('valido');
            elemento.classList.add('invalido');
            icone.className = 'fa-solid fa-circle-xmark';
        }
    }

    // Regras
    atualizarItem(reqTamanho, valor.length >= 8);
    atualizarItem(reqMaiuscula, /[A-Z]/.test(valor));
    atualizarItem(reqMinuscula, /[a-z]/.test(valor));
    atualizarItem(reqEspecial, /[!@#$%^&*(),.?":{}|<>]/.test(valor)); // Regex para especiais
});