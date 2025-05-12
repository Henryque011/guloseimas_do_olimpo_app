<?php

class loginController extends Controller
{
    public function index()
    {
        $dados = array();
        $dados['titulo'] = 'Guloseimas do olimpo - Login';

        $this->carregarViews('login', $dados);
    }

    //metodo de autenticação
    public function autenticar()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_POST['email'] ?? null;
        $senha = $_POST['senha'] ?? null;
        $lembrar = isset($_POST['lembrar']);

        // monta o corpo do POST em JSON
        $postFields = json_encode([
            'email_cliente' => $email,
            'senha_cliente' => $senha
        ]);

        $ch = curl_init(BASE_API . "login");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($postFields)
        ]);

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($statusCode == 200) {
            $data = json_decode($response, true);

            if (!empty($data['token'])) {
                $_SESSION['token'] = $data['token'];

                if ($lembrar) {
                    setcookie('email', $email, time() + (30 * 24 * 60 * 60), "/");
                } else {
                    setcookie('email', '', time() - 3600, "/");
                }                

                header("Location: " . BASE_URL . "index.php?url=menu");
                exit;
            } else {
                $_SESSION['erro_login'] = 'Token não retornado.';
            }
        } else {
            $_SESSION['erro_login'] = 'E-mail ou senha inválidos.';
        }

        header("Location: " . BASE_URL . "index.php?url=login");
        exit;
    }

    public function sair()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (isset($_COOKIE['email'])) {
            setcookie('email', '', time() - 3600, "/");
        }

        if (isset($_COOKIE['senha'])) {
            setcookie('senha', '', time() - 3600, "/");
        }

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header("Location: " . BASE_URL . "index.php?url=login");
        exit;
    }
}
