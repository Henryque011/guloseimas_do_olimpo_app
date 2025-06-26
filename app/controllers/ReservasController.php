<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class ReservasController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['token'])) {
            header("location: " . BASE_URL . "index.php?url=login");
            exit;
        }

        $dadoToken = TokenHelper::validar($_SESSION['token']);
        if (!$dadoToken || empty($dadoToken['id'])) {
            session_destroy();
            unset($_SESSION['token']);
            header("location: " . BASE_URL . "index.php?url=login");
            exit;
        }

        $id_cliente = $dadoToken['id'];

        $urlReservas = BASE_API . 'getReservasPorCliente?id_cliente=' . $id_cliente;

        $ch = curl_init($urlReservas);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $_SESSION['token']
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $reservas = json_decode($response, true);

        $dados = [];
        $dados['titulo'] = 'Minhas Reservas';
        $dados['reservas'] = $reservas;

        $this->carregarViews('reservas', $dados);
    }
}
