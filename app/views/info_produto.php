<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('template/head.php');
require_once('template/header.php');

?>
<main class="site">
    <section class="detalhe-produto" id="detalhes-produto">

        <?php if (!empty($produto)): ?>
            <h2><?= htmlspecialchars($produto['nome_produto']) ?></h2>
            <img src="<?= htmlspecialchars($produto['foto_produto']) ?>"
                alt="<?= htmlspecialchars($produto['alt_foto_produto'] ?? '') ?>"
                style="max-width:300px;">

            <div class="inf_produto">
                <p><strong>Descrição:</strong> <?= nl2br(htmlspecialchars($produto['descricao_produto'] ?? '')) ?></p>
                <p><strong>Preço:</strong> R$ <?= number_format($produto['preco_produto'], 2, ',', '.') ?></p>
                <p><strong>Personalização:</strong> <?= htmlspecialchars($produto['personalizacao_info_produtos'] ?? 'Não especificado') ?></p>
                <p><strong>Forma de pagamento:</strong> <?= htmlspecialchars($produto['forma_pagamento_info_produto'] ?? 'Não informada') ?></p>
                <p><strong>Entrega:</strong> <?= htmlspecialchars($produto['entrega_info_produtos'] ?? 'Não informada') ?></p>
                <p><strong>Reserva:</strong> <?= htmlspecialchars($produto['reserva_info_produtos'] ?? 'Não informada') ?></p>
            </div>
            <div class="btn-reserva">
                <a href="<?= BASE_URL ?>info_produtos/adicionarReserva/<?= $produto['id_produto'] ?>">
                    <button>Reservar Agora</button>
                </a>
            </div>

            <div class="button_voltar">
                <a href="<?php echo BASE_URL; ?>index.php?url=produtos"><button>Voltar para o produtos</button></a>
            </div>
        <?php else: ?>
            <p>Produto não encontrado.</p>
        <?php endif; ?>

    </section>
</main>