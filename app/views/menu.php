<!DOCTYPE html>
<html lang="pt-br">


<?php
require_once('app/views/template/head.php')
?>

<body>

    <?php
    require_once('template/header.php')
    ?>
    <section class="menu">
        <article class="site">
            <h2>bem vindo a</h2>
            <h3>Guloseimas do Olimpo!</h3>
            <p>Olá, Aleatório da silva!</p>
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
                    <a href="index.html"><button>sair<i class="fas fa-sign-out-alt"></i></button></a>
                </div>
            </div>
        </article>
    </section>

    <script src="https://kit.fontawesome.com/bedd2811b0.js" crossorigin="anonymous"></script>
</body>

</html>