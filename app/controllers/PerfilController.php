<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class PerfilController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['token'])) {
            header("Location: " . BASE_URL . "index.php?url=login");
            exit;
        }

        $dadoToken = TokenHelper::validar($_SESSION['token']);
        if (!$dadoToken || empty($dadoToken['id'])) {
            session_destroy();
            unset($_SESSION['token']);
            header("Location: " . BASE_URL . "index.php?url=login");
            exit;
        }

        $id_cliente = $dadoToken['id'];

        // Chamada para API: getPerfilCliente
        $url = BASE_API . 'getPerfilCliente?id_cliente=' . $id_cliente;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $_SESSION['token']
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $perfil = json_decode($response, true);

        $dados = [
            'titulo' => 'Meu Perfil',
            'perfil' => $perfil
        ];

        $this->carregarViews('perfil', $dados);
    }

    public function atualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = [
                'email' => $_POST['email'],
                'nome' => $_POST['nome'],
                'cpf' => $_POST['cpf'],
                'telefone' => $_POST['telefone'],
                'data_nascimento' => $_POST['data_nascimento'],
            ];

            $url = BASE_API . 'atualizarPerfilCliente';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $_SESSION['token']
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
            $response = curl_exec($ch);
            curl_close($ch);

            $resultado = json_decode($response, true);

            if (isset($resultado['sucesso'])) {
                header("Location: " . BASE_URL . "index.php?url=perfil&msg=sucesso");
            } else {
                header("Location: " . BASE_URL . "index.php?url=perfil&msg=erro");
            }
        }
    }
}
