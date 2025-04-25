<!DOCTYPE html>
<html lang="pt-br">

<?php
require_once('template/head.php')
?>

<body>
    <?php
    require_once('template/header.php')
    ?>
    <section class="login">
        <article class="site">
            <h2>Entrar</h2>
            <div class="box_login">
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?url=login/autenticar">
                    <div class="container">
                        <label for="email">
                            <img src="<?php echo BASE_URL; ?>assets/img/email_forms.svg" alt="Icone de Email">
                        </label>
                        <input type="email" name="email" id="email" placeholder="Endereço de email">
                        <hr>
                    </div>
                    <div class="container">
                        <label for="senha"></label>
                        <input type="password" id="senha_entrar" name="senha_entrar" placeholder="Senha">
                        <button type="button" id="toggleSenha"><i class="fa-solid fa-eye fa-rotate-by"></i></button>
                        <hr>
                    </div>
                    <div class="lembrar">
                        <a href="http://localhost/guloseimas_do_olimpophp/public/Recuperarsenha/">Esqueceu a senha?</a>
                        <div class="checkbox">
                            <input type="checkbox" id="lembrar" name="lembrar">
                            <label for="lembrar">
                                <p>lembrar email/senha</p>
                            </label>
                        </div>
                    </div>
                    <div class="space"></div>
                    <div class="button_form">
                        <button type="submit">CONTINUAR</button>
                    </div>
                    <div class="button_voltar">
                        <a href="<?php echo BASE_URL; ?>index.php?url=initial"><button><i class="fa-solid fa-backward"></i>Voltar</button></a>
                    </div>
                </form>
            </div>
        </article>
    </section>

    <script src="https://kit.fontawesome.com/bedd2811b0.js" crossorigin="anonymous"></script>
</body>

</html>