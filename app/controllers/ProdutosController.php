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

        // Buscar todos os produtos na API
        $url = BASE_API . "listarProdutos";
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'AUTHORIZATION: Bearer ' . $_SESSION['token']
        ]);

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode != 200) {
            echo "Erro ao buscar os produtos na API.\nCódigo HTTP: $statusCode";
            exit;
        }

        $produtos = json_decode($response, true);

        $dados = array();
        $dados['titulo'] = 'kiOficina - listar produtos';
        $dados['produtos'] = $produtos;

        $this->carregarViews('produtos', $dados);
    }
}
