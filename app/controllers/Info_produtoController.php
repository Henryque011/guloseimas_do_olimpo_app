<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Info_produtoController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['token'])) {
            header("Location: " . BASE_URL . "index.php?url=login");
            exit;
        }

        $dadoToken = TokenHelper::validar($_SESSION['token']);
        if (!$dadoToken) {
            session_destroy();
            unset($_SESSION['token']);
            header("Location: " . BASE_URL . "index.php?url=login");
            exit;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $dados['erro'] = 'ID do produto não informado.';
            $this->carregarViews('info_produto', $dados);
            return;
        }

        $url = BASE_API . 'getProdutoPorId?id=' . urlencode($id);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $_SESSION['token']
        ]);
        $resposta = curl_exec($ch);
        curl_close($ch);

        if ($resposta === false) {
            $dados['erro'] = 'Erro ao acessar API.';
            $this->carregarViews('info_produto', $dados);
            return;
        }

        $produto = json_decode($resposta, true);

        if (!$produto || !isset($produto['id_produto'])) {
            $dados['erro'] = 'Produto não encontrado.';
            $this->carregarViews('info_produto', $dados);
            return;
        }

        // Já que a API retorna caminho completo da imagem, não precisa corrigir.
        $dados['produtos'][] = $produto;

        $this->carregarViews('info_produto', $dados);
    }
}
