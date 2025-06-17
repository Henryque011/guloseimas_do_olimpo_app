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
            </div>

            <h3>Filtrar por categoria</h3>


            <div id="produtos" class="produtos">

                <?php if (isset($produtos) && is_array($produtos)): ?>
                    <?php foreach ($produtos as $produto): ?>
                        <div class="produto">
                            <h2><?= htmlspecialchars($produto['nome_produto']) ?></h2>
                            <img
                                src="<?= htmlspecialchars($produto['foto_produto']) ?>"
                                alt="<?= htmlspecialchars($produto['alt_foto_produto'] ?? $produto['nome_produto']) ?>">
                            <p>Preço: R$ <?= number_format($produto['preco_produto'], 2, ',', '.') ?></p>
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const produtosContainer = document.getElementById("produtos");
            const precoAtual = document.getElementById("preco-atual");

            // Filtra por preço
            function filtrarPorPreco(precoMaximo) {
                precoAtual.textContent = precoMaximo; // Atualiza o texto do preço selecionado

                fetch(`<?php echo BASE_URL; ?>produtos/filtrarPorPreco?preco=${precoMaximo}`)
                    .then(response => response.text())
                    .then(data => {
                        let cleanedData = data.trim(); // Remove espaços extras

                        if (cleanedData === "") {
                            produtosContainer.innerHTML = "<p class='sem-produtos'>Nenhum produto encontrado dentro desse preço.</p>";
                        } else {
                            produtosContainer.innerHTML = cleanedData; // Substitui os produtos com os filtrados
                            reatribuirEventosFavoritos(); // 🔥 REATRIBUIR EVENTOS AOS NOVOS BOTÕES
                        }
                    })
                    .catch(error => console.error("Erro ao filtrar por preço:", error));
            }
        });
    </script>

</body>

</html>