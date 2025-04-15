<?php

class initialController extends Controller
{
    public function index()
    {

        $dados = array();
        $dados['titulo'] = 'Guloseimas do olimpo - Menu';
        
        $this->carregarViews('menu', $dados);
    }
}
