<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Info_produtoController extends Controller
{
    public function index()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $dados['erro'] = 'ID do produto não informado.';
            $this->carregarViews('info_produto', $dados);
            return;
        }

        if (!isset($_SESSION['token'])) {
            $dados['erro'] = 'Sessão expirada. Faça login novamente.';
            $this->carregarViews('info_produto', $dados);
            return;
        }

        $url = BASE_API . 'getProdutoCompleto?id=' . urlencode($id);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $_SESSION['token']
        ]);
        $resposta = curl_exec($ch);
        curl_close($ch);

        $produto = json_decode($resposta, true);

        // Corrige o caminho da imagem, se necessário
        if (isset($produto['foto_produto']) && strpos($produto['foto_produto'], 'http') !== 0) {
            $caminho = preg_replace('#^produto[/\\\\]#', '', $produto['foto_produto']);
            $produto['foto_produto'] = 'https://agenciatipi02.smpsistema.com.br/aluno/henryque/guloseimas_do_olimpophp/public/uploads/produto/' . rawurlencode($caminho);
        }

        if (!is_array($produto) || empty($produto['id_produto'])) {
            http_response_code(404);
            echo json_encode(['mensagem' => 'Produto não encontrado']);
            return;
        }

        $dados['produto'] = $produto;
        $this->carregarViews('info_produto', $dados);
    }
}
