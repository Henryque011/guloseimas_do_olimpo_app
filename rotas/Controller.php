<?php

class Controller
{

    //Função para carregar a view
    public function carregarViews($views, $dados = array())
    {
        extract($dados);

        require_once '../app/views/' . $views . '.php';
    }
}
