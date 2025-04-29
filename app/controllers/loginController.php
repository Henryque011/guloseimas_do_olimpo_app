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
        $email = $_POST['email'] ?? null;
        $senha = $_POST['senha'] ?? null;
    
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
                header("location: " . BASE_URL . "index.php?url=menu");
                exit;
            } else {
                $_SESSION['erro_login'] = 'Token não retornado';
                header("location: " . BASE_URL . "index.php?url=login");
                exit;
            }
        } else {
            $_SESSION['erro_login'] = 'E-mail ou senha inválidos';
            header("location: " . BASE_URL . "index.php?url=login");
            exit;
        }
    }
    
}
