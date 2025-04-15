<?php

class menuController extends Controller
{
    public function index()
    {

        $dados = array();
        $dados['titulo'] = 'Guloseimas do olimpo - Menu';
        
        $this->carregarViews('menu', $dados);
    }
}
