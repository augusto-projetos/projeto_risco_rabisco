// 1. Espera o HTML ser carregado antes de rodar o script
document.addEventListener("DOMContentLoaded", () => {

    // --- 1. LÓGICA DO MENU MOBILE ---

    const btnMobile = document.getElementById("btn-mobile");
    const containerNav = document.getElementById("container-nav");

    if (btnMobile && containerNav) {
        
        btnMobile.addEventListener("click", () => {
            containerNav.classList.toggle("active");
            const estaAtivo = containerNav.classList.contains("active");
            btnMobile.setAttribute("aria-expanded", estaAtivo);
        });
    }

    // --- 2. LÓGICA DO DROPDOWN DESKTOP ---

    const dropdownButton = document.querySelector(".dropdown-toggle");
    const dropdown = document.querySelector(".menu-usuario .dropdown");

    if (dropdown && dropdownButton) {

        // A. Ação de CLICAR NO BOTÃO "Olá..."
        dropdownButton.addEventListener("click", (e) => {
            dropdown.classList.toggle("active");
            const estaAtivo = dropdown.classList.contains("active");
            dropdownButton.setAttribute("aria-expanded", estaAtivo);
        });

        // B. Ação de CLICAR FORA (para fechar o menu)
        document.addEventListener("click", (e) => {
            if (dropdown.classList.contains("active") && !dropdown.contains(e.target)) {
                dropdown.classList.remove("active");
                dropdownButton.setAttribute("aria-expanded", "false");
            }
        });
    }


    // --- 3. LÓGICA DO BOTÃO "VOLTAR AO TOPO" ---

    // Pega o botão que criamos no cabecalho.php
    const btnTopo = document.getElementById("btn-voltar-ao-topo");

    if (btnTopo) {
        
        // A. Lógica de MOSTRAR/ESCONDER ao rolar
        window.addEventListener("scroll", () => {
            // Pega a distância da rolagem (funciona em todos os navegadores)
            const scrollDist = document.body.scrollTop || document.documentElement.scrollTop;

            if (scrollDist > 300) { 
                // Se rolou mais de 300px, mostra o botão
                // (Usamos 'grid' pois o CSS usa 'place-items: center')
                btnTopo.style.display = "grid"; 
            } else {
                // Senão, esconde
                btnTopo.style.display = "none";
            }
        });

        // B. Lógica de CLICAR para subir suavemente
        btnTopo.addEventListener("click", (e) => {
            // Previne o "pulo" padrão do link href="#"
            e.preventDefault(); 

            // Manda a janela rolar para o topo
            window.scrollTo({
                top: 0,
                behavior: "smooth" // A mágica da rolagem suave!
            });
        });
    }

});