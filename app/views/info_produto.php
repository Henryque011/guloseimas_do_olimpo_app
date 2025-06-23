<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('template/head.php');
require_once('template/header.php');

$id = $_GET['id'] ?? null;
?>

<main class="site">
    <section id="detalhes-produto">
        <?php if (isset($id_produto)): ?>
            <script>
                fetch("<?= BASE_API ?>produtos/getServicoPorId&id=<?= $id_produto ?>")
                    .then(res => res.json())
                    .then(produto => {
                        const container = document.getElementById("detalhes-produto");
                        if (produto && produto.nome_produto) {
                            container.innerHTML = `
                            <h2>${produto.nome_produto}</h2>
                            <img src="${produto.foto_produto}" alt="${produto.alt_foto_produto ?? ''}" style="max-width: 300px;">
                            <p>${produto.descricao_info_produto ?? ''}</p>
                            <p><strong>Preço:</strong> R$ ${parseFloat(produto.preco_produto).toFixed(2).replace('.', ',')}</p>
                        `;
                        } else {
                            container.innerHTML = "<p>Produto não encontrado.</p>";
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        document.getElementById("detalhes-produto").innerHTML = "<p>Erro ao carregar produto.</p>";
                    });
            </script>
        <?php else: ?>
            <p><?= $erro ?? 'ID do produto não foi recebido.' ?></p>
        <?php endif; ?>
    </section>

</main>