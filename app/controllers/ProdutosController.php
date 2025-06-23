<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ProdutosController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['token'])) {
            header("location: " . BASE_URL . "index.php?url=login");
            exit;
        }

        $dadoToken = TokenHelper::validar($_SESSION['token']);
        if (!$dadoToken) {
            session_destroy();
            unset($_SESSION['token']);
            header("location: " . BASE_URL . "index.php?url=login");
            exit;
        }

        // Buscar produtos na API
        $urlProdutos = BASE_API . 'listarProdutos';

        $chProdutos = curl_init($urlProdutos);
        curl_setopt($chProdutos, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chProdutos, CURLOPT_HTTPHEADER, [
            'AUTHORIZATION: Bearer ' . $_SESSION['token']
        ]);
        $responseProdutos = curl_exec($chProdutos);
        curl_close($chProdutos);
        $produtos = json_decode($responseProdutos, true);

        //  Buscar categorias na API
        $urlCategorias = BASE_API . 'listarCategorias';
        $chCategorias = curl_init($urlCategorias);
        curl_setopt($chCategorias, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chCategorias, CURLOPT_HTTPHEADER, [
            'AUTHORIZATION: Bearer ' . $_SESSION['token']
        ]);
        $responseCategorias = curl_exec($chCategorias);
        curl_close($chCategorias);
        $categorias = json_decode($responseCategorias, true);

        // dados
        $dados = [];
        $dados['titulo'] = 'kiOficina - listar produtos';
        $dados['produtos'] = $produtos;
        $dados['categorias'] = $categorias; 

        $this->carregarViews('produtos', $dados);
    }

    public function filtrarPorCategoria()
    {
        if (!isset($_SESSION['token'])) {
            http_response_code(401);
            echo "Usuário não autenticado.";
            return;
        }

        $dadoToken = TokenHelper::validar($_SESSION['token']);
        if (!$dadoToken) {
            session_destroy();
            unset($_SESSION['token']);
            http_response_code(401);
            echo "Token inválido.";
            return;
        }

        $categoriaId = $_GET['id'] ?? null;

        if (!$categoriaId) {
            http_response_code(400);
            echo "<p>Categoria não informada.</p>";
            return;
        }

        $url = BASE_API . 'filtrarPorCategoria?id=' . urlencode($categoriaId);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $_SESSION['token']
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $produtos = json_decode($response, true);

        if (!is_array($produtos) || isset($produtos['erro'])) {
            echo "<p>Erro ao carregar os produtos: " . htmlspecialchars($produtos['erro'] ?? 'Resposta inválida da API.') . "</p>";
            return;
        }

        foreach ($produtos as $produto) {
            echo "<div class='produto'>";
            echo "<h2>" . htmlspecialchars($produto['nome_produto']) . "</h2>";
            echo "<img src='" . htmlspecialchars($produto['foto_produto']) . "' alt='" . htmlspecialchars($produto['alt_foto_produto'] ?? $produto['nome_produto']) . "'>";
            echo "<p>Preço: R$ " . number_format($produto['preco_produto'], 2, ',', '.') . "</p>";
            echo "</div>";
        }
    }
}
