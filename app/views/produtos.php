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