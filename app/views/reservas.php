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
    <section class="list_reserva">
        <article class="site">
            <h2><?= $titulo ?></h2>

            <?php if (!empty($reservas)): ?>
                <ul>
                    <?php foreach ($reservas as $reserva): ?>
                        <li>
                            <strong>Produto:</strong> <?= htmlspecialchars($reserva['nome_produto']) ?><br>
                            <img src="<?= htmlspecialchars($reserva['foto_produto']) ?>" alt="Foto Produto" width="100"><br>
                            <strong>Quantidade:</strong> <?= $reserva['quantidade_reserva'] ?><br>
                            <strong>Data de Entrega:</strong> <?= date('d/m/Y', strtotime($reserva['data_entrega_reserva'])) ?><br>
                            <strong>Status:</strong> <?= $reserva['status_reserva'] ?>
                        </li>
                        <hr>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Você ainda não fez nenhuma reserva.</p>
            <?php endif; ?>

            <div class="btn-produtos">
                <a href="<?php echo BASE_URL; ?>index.php?url=produtos">
                    <button>Produtos</button>
                </a>
            </div>

            <div class="button_voltar">

                <a href="<?php echo BASE_URL; ?>index.php?url=menu"><button>Voltar para o menu</button></a>
            </div>
        </article>
    </section>

</body>

</html>