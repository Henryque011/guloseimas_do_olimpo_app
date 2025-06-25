<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ReservasController extends Controller
{
    public function index()
    {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['token'])) {
            header("location: " . BASE_URL . "index.php?url=login");
            exit;
        }

        $dadoToken = TokenHelper::validar($_SESSION['token']);
        if (!$dadoToken || !isset($dadoToken['id_cliente'])) {
            session_destroy();
            unset($_SESSION['token']);
            header("location: " . BASE_URL . "index.php?url=login");
            exit;
        }

        $id_cliente = $dadoToken['id_cliente'];


        if (!$id_cliente) {
            echo "ID do cliente não encontrado.";
            exit;
        }

        $urlReservas = BASE_API . 'getReservasPorCliente?id_cliente=' . $id_cliente;

        $ch = curl_init($urlReservas);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'AUTHORIZATION: Bearer ' . $_SESSION['token']
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
        exit;

        $reservas = json_decode($response, true);

        $dados = [];
        $dados['titulo'] = 'Minhas Reservas';
        $dados['reservas'] = $reservas;

        $this->carregarViews('reserva', $dados);
    }
}
