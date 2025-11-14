// 1. Espera o HTML ser carregado antes de rodar o script
document.addEventListener("DOMContentLoaded", () => {

    // --- 1. LÓGICA DO MENU MOBILE (O seu código, intocado) ---

    // Seleciona os dois elementos que vamos usar
    const btnMobile = document.getElementById("btn-mobile");
    const containerNav = document.getElementById("container-nav");

    // Verifica se os elementos realmente existem
    if (btnMobile && containerNav) {
        
        // Adiciona a função de "clique" no botão mobile
        btnMobile.addEventListener("click", () => {
            
            // A MÁGICA:
            // Adiciona ou remove a classe 'active' do container
            containerNav.classList.toggle("active");

            // Bônus (Acessibilidade):
            // Avisa leitores de tela se o menu está aberto ou fechado
            const estaAtivo = containerNav.classList.contains("active");
            btnMobile.setAttribute("aria-expanded", estaAtivo);
        });
    }

    // --- 2. NOVA LÓGICA DO DROPDOWN DESKTOP (O que você pediu) ---

    // Seleciona o botão "Olá..." e o 'pai' dele (o <div> .dropdown)
    const dropdownButton = document.querySelector(".dropdown-toggle");
    const dropdown = document.querySelector(".menu-usuario .dropdown");

    if (dropdown && dropdownButton) {

        // A. Ação de CLICAR NO BOTÃO "Olá..."
        dropdownButton.addEventListener("click", (e) => {
            // A MÁGICA:
            // Adiciona/Remove a classe 'active' do elemento .dropdown
            // (O CSS que fizemos vai ler essa classe para mostrar o menu)
            dropdown.classList.toggle("active");
            
            // Bônus (Acessibilidade):
            const estaAtivo = dropdown.classList.contains("active");
            dropdownButton.setAttribute("aria-expanded", estaAtivo);
        });

        // B. Ação de CLICAR FORA (para fechar o menu)
        // Adicionamos um 'click' na página inteira
        document.addEventListener("click", (e) => {
            
            // Se o menu estiver aberto E o clique foi FORA do .dropdown
            if (dropdown.classList.contains("active") && !dropdown.contains(e.target)) {
                
                // Fecha o menu
                dropdown.classList.remove("active");
                // Reseta a acessibilidade
                dropdownButton.setAttribute("aria-expanded", "false");
            }
        });
    }

});