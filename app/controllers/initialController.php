<?php

class InitialController extends Controller
{
    public function index()
    {

        $dados = array();
        $dados['titulo'] = 'Guloseimas do olimpo - Tela inicial';
        
        $this->carregarViews('initial', $dados);
    }
}
