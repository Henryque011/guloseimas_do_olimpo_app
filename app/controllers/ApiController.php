<?php

class ApiController extends Controller
{
    private $clienteModel;

    public function __construct()
    {
        $this->clienteModel = new Cliente();
    }

    public function index()
    {
        $dados = array();
        $dados['titulos'] = 'Área de Atuação - Guloseimas do Olimpo';

        $this->carregarViews('api', $dados);
    }

    private function getAuthorizationHeader()
    {
        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            return trim($_SERVER['HTTP_AUTHORIZATION']);
        }

        if (!empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            return trim($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
        }

        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            foreach ($headers as $key => $value) {
                if (strtolower($key) === 'authorization') {
                    return trim($value);
                }
            }
        }

        return null;
    }

    private function autenticarToken()
    {
        try {
            $authHeader = $this->getAuthorizationHeader();

            if (!$authHeader || !preg_match('/Bearer\s+(.+)/', $authHeader, $matches)) {
                http_response_code(401);
                echo json_encode(['erro' => 'Token não fornecido ou malformado.']);
                exit;
            }

            $token = trim($matches[1]);

            if (!$token || strpos($token, '.') === false) {
                http_response_code(401);
                echo json_encode(['erro' => 'Token inválido ou incompleto.']);
                exit;
            }

            require_once(__DIR__ . '/../../core/TokenHelper.php');
            $TokenHelper = new TokenHelper();

            $dados = $TokenHelper::validar($token);

            if (!$dados || !isset($dados['id'], $dados['email'])) {
                http_response_code(401);
                echo json_encode(['erro' => 'Token inválido ou expirado.']);
                exit;
            }

            $cliente = $this->clienteModel->buscarCliente($dados['email']);

            if (!$cliente || $cliente['id_cliente'] != $dados['id']) {
                http_response_code(403);
                echo json_encode(['erro' => 'Acesso negado.']);
                exit;
            }

            return $cliente;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro interno: ' . $e->getMessage()]);
            exit;
        }
    }

    public function salvarCliente()
    {
        try {
            // Recebe os dados do corpo da requisição
            $input = json_decode(file_get_contents("php://input"), true);

            // Validação básica (pode expandir se quiser)
            $camposObrigatorios = ['nome', 'email', 'cpf', 'data_nasc', 'telefone', 'endereco', 'bairro', 'cidade', 'estado', 'cep', 'senha'];
            foreach ($camposObrigatorios as $campo) {
                if (empty($input[$campo])) {
                    http_response_code(400);
                    echo json_encode(['erro' => "O campo '$campo' é obrigatório."]);
                    return;
                }
            }

            // Pega os dados
            $nome = $input['nome'];
            $email = $input['email'];
            $cpf = $input['cpf'];
            $data_nasc = $input['data_nasc'];
            $telefone = $input['telefone'];
            $endereco = $input['endereco'];
            $bairro = $input['bairro'];
            $cidade = $input['cidade'];
            $estado = $input['estado']; // sigla
            $cep = $input['cep'];
            $senha = password_hash($input['senha'], PASSWORD_DEFAULT);

            // Tenta salvar o cliente
            $sucesso = $this->clienteModel->salvarCliente($nome, $email, $cpf, $data_nasc, $telefone, $endereco, $bairro, $cidade, $estado, $cep, $senha);

            if (!$sucesso) {
                http_response_code(500);
                echo json_encode(['erro' => 'Erro ao salvar cliente.']);
                return;
            }

            // Buscar o cliente para pegar o ID
            $cliente = $this->clienteModel->buscarCliente($email);
            if (!$cliente) {
                http_response_code(500);
                echo json_encode(['erro' => 'Erro ao recuperar cliente salvo.']);
                return;
            }

            // Gerar o token
            require_once(__DIR__ . '/../../core/TokenHelper.php');
            $TokenHelper = new TokenHelper();
            $token = $TokenHelper::gerar([
                'id' => $cliente['id_cliente'],
                'email' => $cliente['email_cliente']
            ]);

            echo json_encode([
                'mensagem' => 'Cliente cadastrado com sucesso.',
                'token' => $token,
                'cliente' => [
                    'id' => $cliente['id_cliente'],
                    'nome' => $cliente['nome_cliente'],
                    'email' => $cliente['email_cliente']
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro interno: ' . $e->getMessage()]);
        }
    }

    public function login()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        $email = $input['email_cliente'] ?? null;
        $senha = $input['senha_cliente'] ?? null;

        if (!$email || !$senha) {
            http_response_code(400);
            echo json_encode(['erro' => 'E-mail ou senha são obrigatórios'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            return;
        }

        $cliente = $this->clienteModel->buscarCliente($email);

        if (!$cliente || $senha !== $cliente['senha_cliente']) {
            http_response_code(401);
            echo json_encode(['erro' => 'E-mail ou senha inválidos'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            return;
        }

        $dadosToken = [
            'id'    => $cliente['id_cliente'],
            'email' => $cliente['email_cliente'],
            'exp'   => time() + 3600 // 1 hora de validade
        ];

        require_once(__DIR__ . '/../../core/TokenHelper.php');
        $TokenHelper = new TokenHelper();
        $token = TokenHelper::gerar($dadosToken);
        //var_dump($token);
        //var_dump(TokenHelper::validar($token));

        if (!class_exists('TokenHelper')) {
            die('TokenHelper não foi carregado!');
        }

        echo json_encode([
            'mensagem' => 'Login realizado com sucesso',
            'token'    => $token
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function cliente()
    {
        try {
            $clienteToken = $this->autenticarToken();
            $input = json_decode(file_get_contents("php://input"), true);

            // Verifica se o e-mail foi enviado
            if (empty($input['email'])) {
                http_response_code(400);
                echo json_encode(['erro' => 'Email não fornecido']);
                return;
            }

            $email = $input['email'];

            // Verifica se o token pertence ao mesmo e-mail requisitado
            // if ($clienteToken['email_cliente'] !== $email) {
            //     http_response_code(403);
            //     echo json_encode(['erro' => 'Acesso negado.']);
            //     return;
            // }

            // Busca exclusivamente por e-mail
            $cliente = $this->clienteModel->buscarPorEmail($email);

            if (!$cliente) {
                http_response_code(404);
                echo json_encode(['erro' => 'Cliente não encontrado']);
                return;
            }

            // Retorna os dados do cliente
            echo json_encode($cliente, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro interno: ' . $e->getMessage()]);
        }
    }
}
