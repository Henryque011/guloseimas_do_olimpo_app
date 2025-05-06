<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


class menuController extends Controller
{
    public function index()
    {

        if (!isset($_SESSION['token'])) {
            header("Location: " . BASE_URL . "index.php?url=login");
            exit;
        }

        $dadosToken = TokenHelper::validar($_SESSION['token']);

        if (!$dadosToken) {
            session_destroy();
            unset($_SESSION['token']);
            header("Location: " . BASE_URL . "index.php?url=login");
            exit;
        }

        $url = BASE_API . "cliente"; 

        $postFields = json_encode([
            'email' => $dadosToken['email']
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $_SESSION['token']
        ]);

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode != 200) {
            echo "Erro ao buscar cliente na API. Código HTTP: $statusCode";
            exit;
        }

        $cliente = json_decode($response, true);


        $dados = array();
        $dados['titulo'] = 'kiOficina - Menu';

        $dados['nome_cliente'] = $cliente['nome_cliente'] ?? 'CLiente';

        $this->carregarViews('menu', $dados);
    }
}
