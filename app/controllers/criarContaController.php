<?php

class criarContaController extends Controller
{
    public function index()
    {
        $dados = ['titulo' => 'Guloseimas do Olimpo - Criar Conta'];
        $erros = []; 

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $camposObrigatorios = ['nome', 'email', 'data_nasc', 'endereco', 'bairro', 'cidade', 'estado', 'cep', 'senha'];

            foreach ($camposObrigatorios as $campo) {
                if (empty($_POST[$campo])) {
                    $erros[] = "O campo '$campo' é obrigatório.";
                }
            }

            if ($_POST['senha'] !== $_POST['confirmar_senha']) {
                $erros[] = "As senhas não coincidem.";
            }

            if (empty($erros)) {
                $dadosCliente = [
                    "nome"      => $_POST['nome'],
                    "email"     => $_POST['email'],
                    "cpf"       => $_POST['cpf'] ?? '',
                    "data_nasc" => $_POST['data_nasc'],
                    "telefone"  => $_POST['telefone'] ?? '',
                    "endereco"  => $_POST['endereco'],
                    "bairro"    => $_POST['bairro'],
                    "cidade"    => $_POST['cidade'],
                    "estado"    => $_POST['estado'],
                    "cep"       => $_POST['cep'],
                    "senha"     => $_POST['senha']
                ];

                $url = BASE_API . "salvarCliente";

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dadosCliente));

                $response = curl_exec($ch);
                $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                $responseData = json_decode($response, true);

                if (($statusCode === 200 || $statusCode === 201) && empty($responseData['erro'])) {
                    $_SESSION['usuario'] = [
                        'nome'  => $dadosCliente['nome'],
                        'email' => $dadosCliente['email']
                    ];
                
                    if (!empty($responseData['token'])) {
                        $_SESSION['token'] = $responseData['token'];
                        $_SESSION['mensagem'] = 'Conta criada com sucesso!';  
                        header('Location: ' . BASE_URL . 'index.php?url=login'); 
                        exit;
                    } else {
                        $erros[] = 'Token não foi retornado pela API.';
                    }
                } else {
                    $mensagemErroAPI = $responseData['erro'] ?? $responseData['mensagem'] ?? 'Erro ao criar conta.';
                    $erros[] = "Erro da API: $mensagemErroAPI (Código HTTP: $statusCode)";
                }
                
            }

            $dados['valores'] = $_POST;
        }

        $dados['erros'] = $erros; 
        $this->carregarViews('criarConta', $dados);
    }
}
