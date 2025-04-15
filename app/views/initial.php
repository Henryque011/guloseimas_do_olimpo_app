<!DOCTYPE html>
<html lang="pt-br">

<?php
require_once('template/head.php')
?>

<body>
    <?php
    require_once('template/header.php')
    ?>
    <section class="inicial">
        <article class="site">
            <h2>bem vindo a</h2>
            <h1>Guloseimas do Olimpo!</h1>
            <div class="space"></div>
            <div class="container">
                <div>
                    <a href="<?php echo BASE_URL; ?>index.php?criarConta">
                        <button>Criar conta<i class="fa-solid fa-user-plus"></i></button>
                    </a>
                </div>
                <div>
                    <a href="<?php echo BASE_URL; ?>index.php?login">
                        <button>Login<i class="fa-solid fa-right-to-bracket fa-fade"></i></button>
                    </a>
                </div>
            </div>
            <div class="space"></div>
            <div class="bg_img">
                <img src="<?php echo BASE_URL; ?>assets/img/bg_doces.svg" alt="">
            </div>
            <div class="socials">
                <h3>siga nossas redes socias</h3>
                <div>
                    <a href="https://w.app/5dk3nm"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><img src="<?php echo BASE_URL; ?>assets/img/ifood_footer.svg" alt=""></a>
                </div>
            </div>
        </article>
    </section>

    <script src="https://kit.fontawesome.com/bedd2811b0.js" crossorigin="anonymous"></script>
</body>

</html>