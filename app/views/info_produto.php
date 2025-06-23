<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('template/head.php');
require_once('template/header.php');
?>

<pre>
TOKEN: <?= $_SESSION['token'] ?? 'não há token' ?>
</pre>

<main class="site">
    <section id="detalhes-produto">
        <?php if (isset($erro)): ?>
            <p><?= $erro ?></p>
        <?php elseif (isset($produtos) && is_array($produtos)): ?>
            <?php foreach ($produtos as $produto): ?>
                <div class="produto">
                    <h2><?= htmlspecialchars($produto['nome_produto']) ?></h2>

                    <img src="<?= htmlspecialchars($produto['foto_produto'], ENT_QUOTES, 'UTF-8') ?>"
                        alt="<?= htmlspecialchars($produto['alt_foto_produto'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        style="max-width: 300px;">

                    <p><?= htmlspecialchars($produto['descricao_info_produto'] ?? '') ?></p>

                    <p><strong>Preço:</strong> R$ <?= number_format($produto['preco_produto'], 2, ',', '.') ?></p>

                    <div class="button_voltar">
                        <a href="index.php?url=produtos"><button>Voltar para os produtos</button></a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Produto não encontrado.</p>
        <?php endif; ?>
    </section>
</main>