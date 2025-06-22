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
                <input type="range" min="0" max="100" value="10" class="escolher-valor" id="escolher-valor">
                <p>Preço: R$ <span id="preco-atual" class="preco-atual">0</span></p>
            </div>

            <div class="categoria">
                <h3>Filtrar por categoria</h3>
                <!-- <pre><?php var_dump($categorias); ?></pre> -->

                <select name="categoria" id="categoria" class="box_categoria" required onchange="filtrarCategoria(this.value)">
                    <option value="">Selecione a categoria</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= htmlspecialchars($categoria['nome_categoria']) ?>">
                            <?= htmlspecialchars($categoria['nome_categoria']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            </div>


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

        function atualizarGradiente() {
            const value = parseInt(range.value);
            const min = parseInt(range.min);
            const max = parseInt(range.max);
            const percent = ((value - min) / (max - min)) * 100;

            range.style.background = `linear-gradient(to right, #1f6e22 0%, #1f6e22 ${percent}%, #ddd ${percent}%, #ddd 100%)`;

            precoAtual.textContent = value;
        }

        range.addEventListener("input", atualizarGradiente);

        // Atualiza gradiente na primeira carga da página
        window.addEventListener("DOMContentLoaded", atualizarGradiente);
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

    <script>
        function filtrarCategoria(categoria) {
            fetch(`<?= BASE_URL ?>produtos/filtrarPorCategoria?categoria=${encodeURIComponent(categoria)}`)
                .then(response => response.text())
                .then(data => {
                    const container = document.getElementById("produtos");
                    container.innerHTML = data.trim() || "<p>Nenhum produto encontrado para essa categoria.</p>";
                })
                .catch(err => console.error("Erro ao filtrar por categoria:", err));
        }
    </script>

</body>

</html>