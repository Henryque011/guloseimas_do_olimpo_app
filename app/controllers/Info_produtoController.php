<?php

class Info_produtoController extends Controller
{
    public function index()
    {
        $dados = [];
        $dados['titulo'] = 'Guloseimas do Olimpo - Detalhes do Produto';

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $dados['erro'] = 'ID do produto não informado.';
        } else {
            $dados['id_produto'] = $id;
        }

        $this->carregarViews('info_produto', $dados);
    }
}
