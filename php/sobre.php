<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós - Risco e Rabisco</title>
    
    <link rel="website icon" type="png" href="../img/rabisco.png">
    
    <!-- Font Awesome (Ícones) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" xintegrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="stylesheet" href="../css/sobre.css">
</head>
<body>

    <!-- 1. CABEÇALHO PÚBLICO (HTML direto) -->
    <header class="cabecalho-publico">
        <a href="../index.php" class="logo-link">
            <img src="../img/logotipo.jpg" alt="Logo Risco e Rabisco">
            <span>Risco e Rabisco</span>
        </a>
        <nav class="nav-publica">
            <a href="../index.php">Início</a>
            <a href="login.php" class="btn-login-publico">Entrar / Cadastrar</a>
        </nav>
        <!-- Botão Mobile (para este cabeçalho) -->
        <button class="btn-mobile-publico" id="btn-mobile-publico" aria-label="Abrir Menu">
            <i class="fa-solid fa-bars"></i>
        </button>
    </header>

    <!-- 2. CONTEÚDO PRINCIPAL (O <main>) -->
    <main class="sobre-container">
    
        <section class="card-sobre">
            <h2><i class="fa-solid fa-store"></i> Sobre a Risco e Rabisco</h2>
            <p>A Risco e Rabisco nasceu como um projeto para a Feira do Senac, com o objetivo de demonstrar uma solução web completa para o gerenciamento de material escolar. Mais do que uma loja, somos uma ferramenta para pais, alunos e amantes de papelaria.</p>
            <p>Nossa plataforma permite que você navegue por um catálogo completo, salve seus itens favoritos e, o mais importante, crie orçamentos detalhados em tempo real.</p>
        </section>
    
        <section class="card-sobre">
            <h2><i class="fa-solid fa-rocket"></i> Como Funciona a Loja?</h2>
            <p>Nosso site não é um e-commerce tradicional! É uma <strong>ferramenta de orçamento</strong>. Não lidamos com pagamentos ou entregas reais. Veja como é simples usar:</p>
            
            <ol class="passo-a-passo">
                <li>
                    <strong>1. Crie sua Conta Grátis:</strong><br>
                    Cadastre-se para ter acesso à sua área pessoal.
                </li>
                <li>
                    <strong>2. Navegue e Adicione:</strong><br>
                    Explore o catálogo e adicione itens ao seu "Orçamento" ou salve-os nos "Favoritos".
                </li>
                <li>
                    <strong>3. Gerencie seu Orçamento:</strong><br>
                    Na página "Orçamento", você pode alterar quantidades, remover itens e ver o valor total da sua lista instantaneamente.
                </li>
                <li>
                    <strong>4. Pronto!</strong><br>
                    Você tem uma lista de materiais completa e valorada, pronta para usar como referência!
                </li>
            </ol>
        </section>
    
        <section class="card-sobre">
            <h2><i class="fa-solid fa-address-book"></i> Contato e Informações</h2>
            <ul class="lista-contato">
                <li>
                    <i class="fa-solid fa-location-dot"></i>
                    <div>
                        <strong>Endereço (Fictício):</strong>
                        <p>Avenida Monsenhor Rafael, nº 1120, Bairro Primavera, Pingo-D’Água, GO.</p>
                    </div>
                </li>
                <li>
                    <i class="fa-solid fa-phone"></i>
                    <div>
                        <strong>Telefone (Fictício):</strong>
                        <p>(33) 94815-7316</p>
                    </div>
                </li>
                <li>
                    <i class="fa-solid fa-envelope"></i>
                    <div>
                        <strong>E-mail (Fictício):</strong>
                        <p>papelariad'agua@gmail.com</p>
                    </div>
                </li>
                <li>
                    <i class="fa-solid fa-clock"></i>
                    <div>
                        <strong>Horário de Funcionamento (Fictício):</strong>
                        <p>Segunda à Sexta: 9h às 18h<br>Sábado: 9h às 13h</p>
                    </div>
                </li>
            </ul>
        </section>
    
    </main>

    <!-- 3. RODAPÉ PÚBLICO (HTML direto) -->
    <footer class="rodape-publico">
        <p style="margin-bottom: 10px; font-size: 0.9rem; color: #ccc;">
            <i class="fa-solid fa-code"></i>
            Desenvolvido por: <strong>Luiz Augusto Leite Freitas</strong>
        </p>
        <p>&copy; 2025 Risco e Rabisco - Projeto Senac</p>
    </footer>

    <!-- Script para o menu mobile deste cabeçalho -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btnMobile = document.getElementById('btn-mobile-publico');
            const nav = document.querySelector('.nav-publica');
            
            if (btnMobile && nav) {
                btnMobile.addEventListener('click', () => {
                    nav.classList.toggle('active');
                });
            }
        });
    </script>

</body>
</html>