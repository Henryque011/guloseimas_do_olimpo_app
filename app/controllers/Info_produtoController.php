<?php
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

        // URL da API
        $urlProduto = BASE_API . 'produtos/getServicoPorId?id=' . urlencode($id);

        // Pega o conteúdo da API
        $resposta = file_get_contents($urlProduto);
        $produto = json_decode($resposta, true);

        if (!$produto || !isset($produto['id_produto'])) {
            $dados['erro'] = 'Produto não encontrado.';
            $this->carregarViews('info_produto', $dados);
            return;
        }

        // Corrige imagem se necessário
        $baseUrlImagem = 'https://agenciatipi02.smpsistema.com.br/aluno/henryque/guloseimas_do_olimpophp/public/uploads/produto/';
        if (strpos($produto['foto_produto'], 'http') !== 0) {
            $foto = preg_replace('#^produto[/\\\\]#', '', $produto['foto_produto']);
            $foto = rawurlencode(str_replace('\\', '/', ltrim($foto, '/')));
            $produto['foto_produto'] = $baseUrlImagem . $foto;
        }

        $dados['produtos'][] = $produto; // envia como array
        $this->carregarViews('info_produto', $dados);
    }
}
