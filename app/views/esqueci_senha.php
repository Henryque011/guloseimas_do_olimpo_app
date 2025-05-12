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
                    <label for="email">E-mail:</label>
                    <input type="email" name="email" id="email" required>

                    <input type="submit" value="Enviar Link" class="btn-link">
                </form>

            </div>
        </article>
    </section>
</body>

</html>