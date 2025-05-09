<?php session_start(); ?>

<?php if (!empty($_SESSION['mensagem'])): ?>
    <div class="sucesso"><?php echo $_SESSION['mensagem'];
                            unset($_SESSION['mensagem']); ?></div>
<?php endif; ?>

<?php if (!empty($dados['mensagem'])): ?>
    <div class="sucesso"><?php echo $dados['mensagem']; ?></div>
<?php endif; ?>

<?php if (!empty($dados['erros'])): ?>
    <div class="erros">
        <?php foreach ($dados['erros'] as $erro): ?>
            <p><?php echo htmlspecialchars($erro); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


<!DOCTYPE html>
<html lang="pt-br">

<?php
require_once('template/head.php')
?>

<body>
    <?php
    require_once('template/header.php')
    ?>
    <section class="criarConta">
        <article class="site">
            <h2>Criar conta</h2>
            <div class="container">
                <form action="index.php?url=criarConta" method="POST">
                    <div class="box_conta">
                        <h5>Nome:</h5>
                        <label for="nome"></label>
                        <input type="text" name="nome" id="nome" placeholder="Nome"
                            value="<?php echo htmlspecialchars($dados['valores']['nome'] ?? ''); ?>"
                            oninput="permitirSomenteLetras(event)">

                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>Email:</h5>
                        <label for="email"></label>
                        <input type="email" name="email" id="email" placeholder="Email"
                            value="<?php echo htmlspecialchars($dados['valores']['email'] ?? ''); ?>" required>
                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>Data de nascimento:</h5>
                        <label for="data_nasc"></label>
                        <input type="date" name="data_nasc" id="data_nasc" placeholder="Data de nascimento"
                            value="<?php echo htmlspecialchars($dados['valores']['data_nasc'] ?? ''); ?>" required>
                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>CPF:</h5>
                        <label for="cpf"></label>
                        <input type="text" name="cpf" id="cpf" placeholder="Cpf"
                            value="<?php echo htmlspecialchars($dados['valores']['cpf'] ?? ''); ?>" oninput="permitirSomenteNumeros(event)">
                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>Telefone:</h5>
                        <label for="telefone"></label>
                        <input type="text" name="telefone" id="telefone" placeholder="Telefone"
                            value="<?php echo htmlspecialchars($dados['valores']['telefone'] ?? ''); ?>" oninput="permitirSomenteNumeros(event)">
                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>Cep:</h5>
                        <label for="cep"></label>
                        <input type="text" name="cep" id="cep" placeholder="Cep"
                            value="<?php echo htmlspecialchars($dados['valores']['cep'] ?? ''); ?>" required oninput="permitirSomenteNumeros(event)">

                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>Endereço:</h5>
                        <label for="Endereco"></label>
                        <input type="text" name="endereco" id="endereco" placeholder="Endereco"
                            value="<?php echo htmlspecialchars($dados['valores']['endereco'] ?? ''); ?>">

                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>Bairro:</h5>
                        <label for="bairro"></label>
                        <input type="text" name="bairro" id="bairro" placeholder="Bairro"
                            value="<?php echo htmlspecialchars($dados['valores']['bairro'] ?? ''); ?>" required>
                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>Estado:</h5>
                        <label for="estado"></label>
                        <input type="text" name="estado" id="estado" placeholder="Estado" maxlength="2"
                            value="<?php echo htmlspecialchars($dados['valores']['estado'] ?? ''); ?>" required
                            oninput="permitirSomenteSiglaEstado(event)">
                        <hr>
                    </div>

                    <div class="box_conta">
                        <h5>Cidade:</h5>
                        <label for="cidade"></label>
                        <input type="text" name="cidade" id="cidade" placeholder="Cidade"
                            value="<?php echo htmlspecialchars($dados['valores']['cidade'] ?? ''); ?>" required
                            oninput="permitirSomenteLetras(event)">
                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>Senha:</h5>
                        <label for="senha"></label>
                        <input type="password" name="senha" id="senha" placeholder="Senha" value="" required
                            autocomplete="off">
                        <button type="button" id="toggleSenha"><i class="fa-solid fa-eye-slash fa-rotate-by"></i></button>
                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>Senha:</h5>
                        <label for="confirmar_senha"></label>
                        <input type="password" name="confirmar_senha" id="confirmar_senha" placeholder="Confirmar Senha"
                            value="" required autocomplete="off">
                        <button type="button" id="toggleSenha"><i class="fa-solid fa-eye-slash fa-rotate-by"></i></button>

                        <hr>
                    </div>
                    <div class="button_form">
                        <button type="submit">Criar conta</button>
                    </div>
                </form>

                <div class="button_voltar">
                    <a href="<?php echo BASE_URL; ?>index.php?url=initial"><i class="fa-solid fa-backward"></i>Voltar</a>
                </div>
            </div>
        </article>
    </section>

    <script src="https://kit.fontawesome.com/bedd2811b0.js" crossorigin="anonymous"></script>

    <script>
        // Permitir somente letras
        function permitirSomenteLetras(event) {
            let input = event.target;
            input.value = input.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚàèìòùÀÈÌÒÙãõÃÕâêîôûÂÊÎÔÛçÇ\s]/g, '');
        }

        // Permitir somente números
        function permitirSomenteNumeros(event) {
            let input = event.target;
            input.value = input.value.replace(/[^0-9]/g, '');
        }
    </script>

    <script>
        document.getElementById('cep').addEventListener('blur', function() {
            let cep = this.value.replace(/\D/g, '');

            if (cep.length !== 8) {
                alert('CEP inválido');
                return;
            }

            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => {
                    if (!response.ok) throw new Error('Erro na requisição do CEP');
                    return response.json();
                })
                .then(data => {
                    if (data.erro) {
                        alert('CEP não encontrado');
                        return;
                    }

                    document.getElementById('endereco').value = data.logradouro || '';
                    document.getElementById('bairro').value = data.bairro || '';
                    document.getElementById('cidade').value = data.localidade || '';
                    document.getElementById('estado').value = data.uf || '';
                })
                .catch(error => {
                    console.error('Erro ao buscar CEP:', error);
                    alert('Erro ao buscar CEP');
                });
        });
    </script>

    <script>
        function permitirSomenteSiglaEstado(event) {
            let input = event.target;
            input.value = input.value.toUpperCase().replace(/[^A-Z]/g, '').slice(0, 2);
        }
    </script>

    <script>
        document.getElementById('toggleSenha').addEventListener('click', function() {
            let senhaInputs = [
                document.getElementById('senha'),
                document.getElementById('confirmar_senha')
            ];
            let icon = this.querySelector('i');

            senhaInputs.forEach(input => {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });
        });
    </script>

</body>

</html>