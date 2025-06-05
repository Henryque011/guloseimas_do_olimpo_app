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
    <section class="produto">
        <article class="site">
            <h2>Filtar por preços</h2>
            <div class="filtrar">
                <input type="range" min="0" max="100" value="50" class="escolher-valor" id="escolher-valor">
                <p>Preço: R$ <span id="preco-atual">0</span></p>
                <h3>Filtrar por categoria</h3>
            </div>

            <div class="produtos">
                
                <?php if (isset($produtos) && is_array($produtos)): ?>
                    <?php foreach ($produtos as $produto): ?>
                        <div class="card-produto">
                            <h4><?= htmlspecialchars($produto['nome_produto']) ?></h4>
                            <p><?= htmlspecialchars($produto['descricao_produto']) ?></p> 
                            <p>Preço: R$ <?= number_format($produto['preco_produto'], 2, ',', '.') ?></p>
                            <p>Categoria: <?= htmlspecialchars($produto['id_categoria']) ?></p>

                            <?php if (!empty($produto['foto_produto'])): ?>
                                <img src="<?= BASE_URL . 'public/assets/' . $produto['foto_produto'] ?>"
                                    alt="<?= htmlspecialchars($produto['alt_foto_produto']) ?>"
                                    width="150">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum produto encontrado.</p>
                <?php endif; ?>
            </div>
        </article>

        
    </section>

    <script>
        const range = document.getElementById("escolher-valor");
        const precoAtual = document.getElementById("preco-atual");

        range.addEventListener("input", () => {
            const value = range.value;
            const max = range.max;
            const percent = (value / max) * 100;

            range.style.background = `linear-gradient(to right, #1f6e22 ${percent}%, #ddd ${percent}%)`;
            precoAtual.textContent = value;
        });
    </script>
</body>

</html>