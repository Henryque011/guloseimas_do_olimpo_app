<?php session_start();
?>
<?php if (!empty($dados['mensagem'])): ?>
    <div class="sucesso"><?php echo $dados['mensagem']; ?></div>
<?php endif; ?>

<?php if (!empty($dados['erros'])): ?>
    <div class="erros">
        <?php foreach ($dados['erros'] as $erro): ?>
            <p><?php echo $erro; ?></p>
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
                        <input type="text" name="nome" id="nome" placeholder="Nome" value=""
                            oninput="permitirSomenteLetras(event)">
                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>Email:</h5>
                        <label for="email"></label>
                        <input type="email" name="email" id="email" placeholder="Email" value="" required>
                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>Data de nascimento:</h5>
                        <label for="data_nasc"></label>
                        <input type="date" name="data_nasc" id="data_nasc" placeholder="Data de nascimento" value="" required
                            autocomplete="off">
                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>CPF:</h5>
                        <label for="cpf"></label>
                        <input type="text" name="cpf" id="cpf" placeholder="Cpf" required oninput="permitirSomenteNumeros(event)">
                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>Telefone:</h5>
                        <label for="telefone"></label>
                        <input type="text" name="telefone" id="telefone" placeholder="Telefone" required oninput="permitirSomenteNumeros(event)">
                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>Cep:</h5>
                        <label for="cep"></label>
                        <input type="text" name="cep" id="cep" placeholder="Cep" value="" required required
                            oninput="permitirSomenteNumeros(event)">
                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>Endereço:</h5>
                        <label for="Endereco"></label>
                        <input type="text" name="endereco" id="endereco" placeholder="Endereco" value="">
                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>Bairro:</h5>
                        <label for="bairro"></label>
                        <input type="text" name="bairro" id="bairro" placeholder="Bairro" value="" required>
                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>Estado:</h5>
                        <label for="estado"></label>
                        <input type="text" name="estado" id="estado" placeholder="Estado" maxlength="2" value="" required
                            oninput="permitirSomenteSiglaEstado(event)">
                        <hr>
                    </div>

                    <div class="box_conta">
                        <h5>Cidade:</h5>
                        <label for="cidade"></label>
                        <input type="text" name="cidade" id="cidade" placeholder="Cidade" value="" required required
                            oninput="permitirSomenteLetras(event)">
                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>Senha:</h5>
                        <label for="senha"></label>
                        <input type="password" name="senha" id="senha" placeholder="Senha" value="" required
                            autocomplete="off">
                        <hr>
                    </div>
                    <div class="box_conta">
                        <h5>Senha:</h5>
                        <label for="confirmar_senha"></label>
                        <input type="password" name="confirmar_senha" id="confirmar_senha" placeholder="Confirmar Senha"
                            value="" required autocomplete="off">
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


</body>

</html>