<?php

class ApiController extends Controller
{
    private $clienteModel;
    private $produtoModel;

    public function __construct()
    {
        $this->clienteModel = new Cliente();
        $this->produtoModel = new Produto();
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
            $input = json_decode(file_get_contents("php://input"), true);

            $camposObrigatorios = ['nome', 'email', 'cpf', 'data_nasc', 'telefone', 'endereco', 'bairro', 'cidade', 'estado', 'cep', 'senha'];
            foreach ($camposObrigatorios as $campo) {
                if (empty($input[$campo])) {
                    http_response_code(400);
                    echo json_encode(['erro' => "O campo '$campo' é obrigatório."]);
                    return;
                }
            }

            $nome      = $input['nome'];
            $email     = $input['email'];
            $cpf       = $input['cpf'];
            $data_nasc = $input['data_nasc'];
            $telefone  = $input['telefone'];
            $endereco  = $input['endereco'];
            $bairro    = $input['bairro'];
            $cidade    = $input['cidade'];
            $estado    = $input['estado']; // sigla
            $cep       = $input['cep'];
            $senha     = $input['senha'];

            $sucesso = $this->clienteModel->salvarCliente($nome, $email, $cpf, $data_nasc, $telefone, $endereco, $bairro, $cidade, $estado, $cep, $senha);

            if (!$sucesso) {
                http_response_code(500);
                echo json_encode(['erro' => 'Erro ao salvar cliente.']);
                return;
            }

            $cliente = $this->clienteModel->buscarCliente($email);
            if (!$cliente) {
                http_response_code(500);
                echo json_encode(['erro' => 'Erro ao recuperar cliente salvo.']);
                return;
            }

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
            if ($clienteToken['email_cliente'] !== $email) {
                http_response_code(403);
                echo json_encode(['erro' => 'Acesso negado.']);
                return;
            }

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

    public function getClienteById($id)
    {
        try {
            $cliente = $this->autenticarToken();

            // Verifica se o token está válido e pertence ao cliente requisitado
            if (!$cliente || !isset($cliente['id_cliente']) || $cliente['id_cliente'] != $id) {
                http_response_code(403);
                echo json_encode(['erro' => 'Acesso negado.']);
                return;
            }

            // Busca os dados completos do cliente no banco
            $dados = $this->clienteModel->getClienteById($id);

            if (!$dados) {
                http_response_code(404);
                echo json_encode(['erro' => 'Cliente não encontrado']);
                return;
            }

            echo json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'erro' => 'Erro interno no servidor',
                'detalhe' => $e->getMessage()
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }


    public function recuperarSenha()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['erro' => 'Método não permitido'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $email = filter_input(INPUT_POST, 'email_cliente', FILTER_SANITIZE_EMAIL);

        if (!$email) {
            http_response_code(400);
            echo json_encode(['erro' => 'E-mail obrigatório'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $cliente = $this->clienteModel->buscarCliente($email);

        if (!$cliente) {
            http_response_code(404);
            echo json_encode(['erro' => 'E-mail não encontrado'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->clienteModel->salvarTokenRecuperacao($cliente['id_cliente'], $token, $expira);

        // ENVIO DE E-MAIL
        require_once("vendors/phpmailer/PHPMailer.php");
        require_once("vendors/phpmailer/SMTP.php");
        require_once("vendors/phpmailer/Exception.php");

        $mail = new PHPMailer\PHPMailer\PHPMailer();

        try {
            $mail->isSMTP();
            $mail->Host       = EMAIL_HOST;
            $mail->Port       = EMAIL_PORT;
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = 'tls';
            $mail->Username   = EMAIL_USER;
            $mail->Password   = EMAIL_PASS;

            // $mail->SMTPDebug = 2;
            // $mail->Debugoutput = 'html'; 

            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            $mail->setFrom(EMAIL_USER, 'Guloseimas do Olimpo');
            $mail->addAddress($cliente['email_cliente'], $cliente['nome_cliente']);
            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Senha';

            $link = "https://agenciatipi02.smpsistema.com.br/aluno/henryque/guloseimas_do_olimpophp/public/api/redefinirSenha?token=$token";

            // https://agenciatipi02.smpsistema.com.br/aluno/henryque/guloseimas_do_olimpophp/public/api/
            // $link = "https://360criativo.com.br/api/redefinirSenha?token=$token";

            $mail->msgHTML("
            Olá, {$cliente['nome_cliente']},<br><br>
            Recebemos uma solicitação para redefinir sua senha.<br>
            Clique no link abaixo para criar uma nova senha:<br><br>
            <a href='$link'>$link</a><br><br>
            Se você não fez essa solicitação, ignore este e-mail.
        ");
            $mail->AltBody = "OlÃ¡ {$cliente['nome_cliente']}, acesse $link para redefinir sua senha.";

            $mail->send();

            echo json_encode(['mensagem' => 'Um link de redefinição foi enviado para seu e-mail'], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao enviar e-mail', 'detalhes' => $mail->ErrorInfo], JSON_UNESCAPED_UNICODE);
            // die("Erro ao enviar e-mail: " . $mail->ErrorInfo);
        }
    }

    /** View para redefinir senha */
    public function redefinirSenha()
    {
        $dados = array();
        $dados['titulo'] = 'Recuperação de senha - Guloseimas do Olimpo';
        $this->carregarViews('redefinir_senha', $dados);
    }

    /** O usuÃ¡rio acessa o link com o token, define uma nova senha e salva. */
    public function resetarSenha()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['erro' => 'MÃ©todo nÃ£o permitido'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $token = $_POST['token'] ?? null;
        $novaSenha = $_POST['nova_senha'] ?? null;

        if (!$token || !$novaSenha) {
            http_response_code(400);
            echo json_encode(['erro' => 'Token e nova senha sÃ£o obrigatÃ³rios'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $cliente = $this->clienteModel->getClientePorToken($token);

        if (!$cliente || strtotime($cliente['token_expira']) < time()) {
            http_response_code(403);
            echo json_encode(['erro' => 'Token invÃ¡lido ou expirado'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $atualizado = $this->clienteModel->upadateSenha($cliente['id_cliente'], $novaSenha);

        // if ($atualizado) {
        //     $this->clienteModel->limparTokenRecuperacao($cliente['id_cliente']);
        //     $dados['mensagem'] = 'Senha redefinida com sucesso';
        //     $this->carregarViews('home', $dados);
        // } else {
        //     http_response_code(500);
        //     $dados['erro'] = 'Erro ao atualizar a senha';
        //     $this->carregarViews('home', $dados);
        // }
        if ($atualizado) {
            $this->clienteModel->limparTokenRecuperacao($cliente['id_cliente']);
            $dados['mensagem'] = 'Senha redefinida com sucesso.';
            $this->carregarViews('sucesso_senha', $dados);
        } else {
            http_response_code(500);
            $dados['mensagem'] = 'Erro ao atualizar a senha.';
            $this->carregarViews('sucesso_senha', $dados);
        }
    }

    public function listarProdutos()
    {
        $produtos = $this->produtoModel->getTodosProdutos(100);

        if (empty($produtos)) {
            http_response_code(404);
            echo json_encode(['mensagem' => 'Nenhum produto encontrado.']);
            return;
        }

        $baseUrlImagem = 'https://agenciatipi02.smpsistema.com.br/aluno/henryque/guloseimas_do_olimpophp/public/produtos/';

        foreach ($produtos as &$produto) {
            if (strpos($produto['foto_produto'], 'http') !== 0) {
                $produto['foto_produto'] = $baseUrlImagem . ltrim($produto['foto_produto'], '/');
            }
        }

        echo json_encode($produtos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function listarImagens()
    {
        $produtos = $this->produtoModel->getProduto();

        $baseUrlImagem = 'https://agenciatipi02.smpsistema.com.br/aluno/henryque/guloseimas_do_olimpophp/public/produtos/';

        foreach ($produtos as &$produto) {
            if (strpos($produto['foto_produto'], 'http') !== 0) {
                $produto['foto_produto'] = $baseUrlImagem . ltrim($produto['foto_produto'], '/');
            }
        }

        if (empty($produtos)) {
            http_response_code(404);
            echo json_encode(['mensagem' => 'Nenhum produto encontrado.']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode($produtos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function listarCategorias()
    {
        $categorias = $this->produtoModel->getTodasCategorias();

        if (empty($categorias)) {
            http_response_code(404);
            echo json_encode(['mensagem' => 'Nenhuma categoria encontrada.']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode($categorias, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function filtrarPorCategoria()
    {
        // Pega a categoria da URL: ?categoria=Bolos
        $categoria = $_GET['categoria'] ?? null;

        // Caso nenhum parâmetro tenha sido passado
        if (!$categoria) {
            http_response_code(400);
            echo json_encode(['erro' => 'Categoria não informada']);
            return;
        }

        $produtos = $this->produtoModel->getPg_produtos($categoria, 'Ativo');

        $baseUrlImagem = 'https://agenciatipi02.smpsistema.com.br/aluno/henryque/guloseimas_do_olimpophp/public/produtos/';

        foreach ($produtos as &$produto) {
            if (strpos($produto['foto_produto'], 'http') !== 0) {
                $produto['foto_produto'] = $baseUrlImagem . ltrim($produto['foto_produto'], '/');
            }
        }

        if (empty($produtos)) {
            http_response_code(404);
            echo json_encode(['mensagem' => 'Nenhum produto encontrado para essa categoria.']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode($produtos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
