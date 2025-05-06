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
                        <input type="password" id="senha_entrar" name="senha" placeholder="Senha">
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

    <?php if (!empty($_SESSION['erro_login'])): ?>
        <div id="erroModal" class="modal" style="display: flex; flex-direction: column; justify-content: center; align-items:center; text-align: center; position: fixed; top: 30%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border: 1px solid; border-radius: 20px; z-index: 999; font-family: Poly;">
            <p style="margin: 0;"><?php echo $_SESSION['erro_login']; ?></p>
            <button onclick="document.getElementById('erroModal').style.display='none'" style="margin-top:20px; width: 100px;
            text-align: center;">Fechar</button>
        </div>
        <script>
            setTimeout(() => {
                const modal = document.getElementById('erroModal');
                if (modal) modal.style.display = 'none';
            }, 5000); // Fecha automaticamente após 5 segundos
        </script>
        <?php unset($_SESSION['erro_login']); ?>
    <?php endif; ?>

    <script>
        document.getElementById('toggleSenha').addEventListener('click', function() {
            let inputSenha = document.getElementById('senha_entrar');
            if (inputSenha.type === 'password') {
                inputSenha.type = 'text';
                this.textContent = '👁️'; // Ícone para mostrar a senha
            } else {
                inputSenha.type = 'password';
                this.textContent = '🙈'; // Ícone para esconder a senha
            }
        });
    </script>

    <script src="https://kit.fontawesome.com/bedd2811b0.js" crossorigin="anonymous"></script>
</body>

</html>