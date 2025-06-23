<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('template/head.php');
require_once('template/header.php');

?>

<main class="site">
    <section class="detalhe-produto" id="detalhes-produto">
        <?php if (isset($erro)): ?>
            <p><?= htmlspecialchars($erro) ?></p>
        <?php elseif (isset($produtos) && is_array($produtos)): ?>
            <?php foreach ($produtos as $produto): ?>
                <h2><?= htmlspecialchars($produto['nome_produto']) ?></h2>
                <img src="<?= htmlspecialchars($produto['foto_produto']) ?>" alt="<?= htmlspecialchars($produto['alt_foto_produto'] ?? '') ?>" style="max-width:300px;">
                <p><?= htmlspecialchars($produto['descricao_produto'] ?? '') ?></p>
                <p><strong>Preço:</strong> R$ <?= number_format($produto['preco_produto'], 2, ',', '.') ?></p>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Produto não encontrado.</p>
        <?php endif; ?>

    </section>
</main>