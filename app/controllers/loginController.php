<?php

class loginController extends Controller
{
    public function index()
    {

        $dados = array();
        $dados['titulo'] = 'Guloseimas do olimpo - Login';
        
        $this->carregarViews('login', $dados);
    }
}
