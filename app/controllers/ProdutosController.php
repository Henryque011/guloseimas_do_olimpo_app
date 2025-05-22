<?php

class ProdutosController extends Controller{
    public function index()
    {
        $dados = array();
        $dados['titulo'] = 'Guloseimas do olimpo - Produtos';

        $this->carregarViews('produtos', $dados);
    }
}