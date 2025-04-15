<?php

class criarContaController extends Controller
{
    public function index()
    {

        $dados = array();
        $dados['titulo'] = 'Guloseimas do olimpo - Criar Conta';
        
        $this->carregarViews('criarConta', $dados);

        if (!isset($_SESSION['criarConta'])) {
            header("Location: " . BASE_URL . "index.php?url=criarConta");
            exit;
        }
    }
}
