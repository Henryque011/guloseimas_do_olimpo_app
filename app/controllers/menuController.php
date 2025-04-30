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

        // buscar cliente na api
        $url = BASE_API  . "cliente/" . $dadosToken['id'];

        // Reconhecimento da chave(Inicializa uma sessão cURL)
        $ch = curl_init($url);

        // Definir que o conteudo venha com a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'authorization: Bearer ' . $_SESSION['token']
        ]);

        // recebe os dados dessa solicitação
        $response = curl_exec($ch);

        // Obtém o código HTTP da resposta (200, 400, 401)
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Encerra a sessão Curl
        curl_close($ch);

        if ($statusCode != 200) {
            echo "Erro ao buscar cliente na API.\n
            Código HTTP: $statusCode";
        }

        // separa os dados em 'campos'
        $cliente = json_decode($response, true);

        $dados = array();
        $dados['titulo'] = 'kiOficina - Menu';

        $dados['nome_cliente'] = $cliente['nome_cliente'] ?? 'CLiente';

        $this->carregarViews('menu', $dados);
    }
}
