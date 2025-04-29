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
            <p>Olá,<?php echo $nome_cliente ?>!</p>
            <div class="container">
                <div>
                    <a href="#">
                        <button>Produtos</button>
                    </a>
                </div>
                <div>
                    <a href="#">
                        <button>Reservas</button>
                    </a>
                </div>
                <div>
                    <a href="#">
                        <button>Novidades</button>
                    </a>
                </div>
                <div>
                    <a href="#">
                        <button>Meu Perfil</button>
                    </a>
                </div>
                <div class="button_voltar">
                    <a href="<?php echo BASE_URL; ?>index.php?url=initial"><button>sair<i class="fas fa-sign-out-alt"></i></button></a>
                </div>
            </div>
        </article>
    </section>

    <script src="https://kit.fontawesome.com/bedd2811b0.js" crossorigin="anonymous"></script>
</body>

</html>