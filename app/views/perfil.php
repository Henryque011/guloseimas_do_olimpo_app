<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<?php
require_once('template/head.php')
?>

<body>
    <?php
    require_once('template/header.php')
    ?>
    <section class="perfil">
        <article class="site">
            <h2>Meu Perfil</h2>

            <form action="index.php?url=perfil/atualizar" method="POST">

                <div class="box_conta">
                    <h5>Nome</h5>
                    <input type="text" name="nome" value="<?= htmlspecialchars($perfil['nome_cliente']) ?>" placeholder="Nome completo" required>
                    <hr>
                </div>

                <div class="box_conta">
                    <h5>Email</h5>
                    <input type="email" name="email" value="<?= htmlspecialchars($perfil['email_cliente']) ?>" placeholder="Email" readonly>
                    <hr>
                </div>

                <div class="box_conta">
                    <h5>Senha</h5>
                    <input type="password" name="senha" placeholder="Nova senha">
                    <hr>
                </div>

                <div class="box_conta">
                    <h5>Confirme nova senha</h5>
                    <input type="password" name="confirma_senha" placeholder="Confirmar nova senha">
                    <hr>
                </div>

                <div class="acoes_perfil">
                    <button type="submit" class="btn_salvar">Salvar alterações</button>
                </div>
            </form>
        </article>
    </section>
</body>

</html>