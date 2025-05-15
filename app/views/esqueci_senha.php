<!DOCTYPE html>
<html lang="pt-br">
<?php
require_once('template/head.php')
?>

<body>
    <?php
    require_once('template/header.php')
    ?>
    <section class="esqueci_senha">
        <article class="site">
            <h2>Recuperar senha</h2>
            <div class="container">
                <h3>Informe um email para recuperação</h3>
                <form action="">
                    <input type="email" name="email" id="email" placeholder="email" required>
                    <hr>
                    <input type="submit" value="Enviar Link" class="btn-link">
                </form>
            </div>

        </article>
        <div class="button_voltar">
            <a href="<?php echo BASE_URL; ?>index.php?url=initial"><i class="fa-solid fa-backward"></i>Voltar</a>
        </div>
    </section>

</body>

</html>