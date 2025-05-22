<!DOCTYPE html>
<html lang="pt-br">
<?php
session_start();
require_once('template/head.php')
?>

<body>
    <?php
    require_once('template/header.php')
    ?>
    <section class="esqueci_senha">
        <article class="site">
            <h2>Recuperar senha</h2>
            <div class="container">

                <?php if (isset($_SESSION['flash'])): ?>
                    <div class="alert <?= $_SESSION['flash']['tipo'] ?>">
                        <?= $_SESSION['flash']['mensagem'] ?>
                    </div>
                    <?php unset($_SESSION['flash']); ?>
                <?php endif; ?>

                <h3>Informe um email para recuperação</h3>
                <form action="<?php echo BASE_URL; ?>index.php?url=login/enviarRecuperacao" method="POST">
                    <input type="email" name="email" id="email" placeholder="email" required>
                    <hr>
                    <input type="submit" value="Enviar Link" class="btn-link">
                </form>
            </div>

        </article>
        <div class="button_voltar">
            <a href="<?php echo BASE_URL; ?>index.php?url=initial"><i class="fa-solid fa-backward"></i>Voltar</a>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const params = new URLSearchParams(window.location.search);
            const status = params.get('status');
            const msg = params.get('msg');

            if (status && msg) {
                const modal = document.createElement('div');
                modal.className = 'modal-alert ' + status;
                modal.innerHTML = `
            <div class="modal-content">
                <p>${decodeURIComponent(msg)}</p>
                <button onclick="fecharModal()">Fechar</button>
            </div>
        `;
                document.body.appendChild(modal);
                setTimeout(() => {
                    fecharModal();
                }, 500000);
            }

            window.fecharModal = function() {
                const modal = document.querySelector('.modal-alert');
                if (modal) modal.remove();
            };
        });
    </script>

    <style>
        .modal-alert {
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            border: 2px solid #ccc;
            padding: 20px;
            z-index: 9999;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .modal-alert.sucesso {
            border-color: #28a745;
        }

        .modal-alert.erro {
            border-color: #dc3545;
        }

        .modal-content {
            align-items: center;
            text-align: center;
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        .modal-alert .modal-content button {
            margin-top: 10px;
            width: 100px;
            height: 30px;
        }
    </style>

</body>

</html>