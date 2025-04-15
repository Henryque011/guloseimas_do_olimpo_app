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
                <form action="#" method="POST">
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
                        <input type="text" name="estado" id="estado" placeholder="Estado" value="" required>
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
                </form>
                <div class="button_form">
                    <button type="submit">Criar conta</button>
                </div>
                <div class="button_voltar">
                    <a href="<?php echo BASE_URL; ?>index.php?url=initial"><button><i class="fa-solid fa-backward"></i>Voltar</button></a>
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

</body>

</html>