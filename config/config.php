<?php 

define('BASE_URL', 'http://localhost/guloseimas_do_olimpoapp/public/');

define('BASE_API','https://agenciatipi02.smpsistema.com.br/aluno/henryque/guloseimas_do_olimpophp/public/api/');

spl_autoload_register(function ($class) {
    if (file_exists('../app/controllers/' . $class . '.php')) {
        require_once '../app/controllers/' . $class . '.php';
    }
    if (file_exists('../rotas/' . $class . '.php')) {
        require_once '../rotas/' . $class . '.php';
    }
});
