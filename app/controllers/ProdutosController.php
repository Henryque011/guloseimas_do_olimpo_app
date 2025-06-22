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

        // 🔽 Buscar categorias na API
        $urlCategorias = BASE_API . 'listarCategorias';
        $chCategorias = curl_init($urlCategorias);
        curl_setopt($chCategorias, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chCategorias, CURLOPT_HTTPHEADER, [
            'AUTHORIZATION: Bearer ' . $_SESSION['token']
        ]);
        $responseCategorias = curl_exec($chCategorias);
        curl_close($chCategorias);
        $categorias = json_decode($responseCategorias, true);

        // Monta os dados
        $dados = [];
        $dados['titulo'] = 'kiOficina - listar produtos';
        $dados['produtos'] = $produtos;
        $dados['categorias'] = $categorias; // 👈 AGORA EXISTE

        $this->carregarViews('produtos', $dados);
    }
}
