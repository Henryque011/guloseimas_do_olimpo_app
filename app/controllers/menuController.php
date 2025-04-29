<?php

class menuController extends Controller
{
    public function index()
    {
        // Verifica se o token está presente
        if (!isset($_SESSION['token'])) {
            header("Location: " . BASE_URL . "index.php?url=login");
            exit;
        }

        // Valida o token
        $dadosToken = TokenHelper::validar($_SESSION['token']);

        if (!$dadosToken) {
            session_destroy();
            unset($_SESSION['token']);
            header("Location: " . BASE_URL . "index.php?url=login");
            exit;
        }

        // Se passou pela validação, carrega a view
        $dados = array();
        $dados['titulo'] = 'Guloseimas do olimpo - Menu';

        $this->carregarViews('menu', $dados);
    }
}
