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

        //fazer a requisição da API DE LOGIN
        $url = BASE_API . "login?email_cliente=$email&senha_cliente=$senha";

        // Reconhecimento da chave(Inicializa uma sessão cURL)
        $ch = curl_init($url);

        // Definir que o conteudo venha com a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // recebe os dados em campos 
        // $response = file_get_contents($url); troca na forma de resposta
        $response = curl_exec($ch);

        // separa os dados em 'campos'
        // Obtém o código HTTP da resposta (200, 400, 401)

        // $data = json_decode($response, true);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Encerra a sessão Curl
        curl_close($ch);

        if ($statusCode == 200) {

            $data = json_decode($response, true);
            if (!empty($data['token'])) {
                // $idToken = json_decode(base64_decode($data['token']), true);
                // $id_cliente = $idToken['id'] ?? null;

                $_SESSION['token'] = $data['token'];
                // $_SESSION['id_cliente'] = $id_cliente;
                header("location: " . BASE_URL . "index.php?url=menu");
                exit;
            } else {
                header("location: " . BASE_URL . "index.php?url=login");
                exit;
            }
        } else {
            echo "login Inválido - ";
        }
    }
}
