<!DOCTYPE html>
<html lang="pt-br">

<?php
require_once('template/head.php')
?>

<body>

    <?php
    require_once('template/header.php')
    ?>
    <section class="menu">
        <article class="site">
            <h2>bem vindo a</h2>
            <h3>Guloseimas do Olimpo!</h3>
            <p>Olá, <?php echo $nome_cliente ?>!</p>
            <div class="container">
                <div>
                    <a href="<?php echo BASE_URL; ?>index.php?url=produtos">
                        <button>Produtos</button>
                    </a>
                </div>
                <div>
                    <a href="#">
                        <button>Reservas</button>
                    </a>
                </div>
                <!-- <div>
                    <a href="#">
                        <button>Novidades</button>
                    </a>
                </div> -->
                <div>
                    <a href="#">
                        <button>Meu Perfil</button>
                    </a>
                </div>
                <div class="button_voltar">
                    <a href="<?php echo BASE_URL; ?>index.php?url=login/sair"><button>sair<i class="fas fa-sign-out-alt"></i></button></a>
                </div>
            </div>
        </article>
    </section>
    <?php if (!empty($_SESSION['mensagem'])): ?>
        <div id="mensagemModal" class="modal" style="
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        position: fixed;
        top: 30%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #e6ffe6;
        color: #006600;
        padding: 20px;
        border: 2px solid #006600;
        border-radius: 10px;
        z-index: 9999;
        font-family: Poly;
        min-width: 300px;
    ">
            <p><?php echo $_SESSION['mensagem']; ?></p>
            <button onclick="document.getElementById('mensagemModal').style.display='none'" style="
            margin-top: 20px;
            padding: 8px 16px;
            background: #006600;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        ">Fechar</button>
        </div>

        <script>
            setTimeout(() => {
                const modal = document.getElementById('mensagemModal');
                if (modal) modal.style.display = 'none';
            }, 5000);
        </script>

        <?php unset($_SESSION['mensagem']); ?>
    <?php endif; ?>

    <script src="https://kit.fontawesome.com/bedd2811b0.js" crossorigin="anonymous"></script>
</body>

</html>