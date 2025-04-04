<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SMP PROMOBRANDING</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/transition-style">

</head>
<body>
    <div class="main-container" transition-style="in:circle:top-left">
        <div class="logo-section">
            <img src="/public/img/logo_promobranding.png" alt="Promobranding Logo" class="logo">
        </div>
        <form class="box login" action="/app/controllers/LoginController.php" method="POST" autocomplete="off">
            <p class="has-text-centered">
                <i class="fas fa-user-circle fa-5x"></i>
            </p>
            <h5 class="title is-5 has-text-centered text-white">Ingresa correctamente tus datos</h5>

            <div class="field">
                <label class="label">Usuario</label>
                <div class="input-container">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <path fill="currentColor" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4Zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4Z"/>
                    </svg>
                    <input class="input" type="email" name="email" maxlength="20" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Contraseña</label>
                <div class="input-container">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <path fill="currentColor" d="M12 17c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2ZM17 9V7c0-2.76-2.24-5-5-5S7 4.24 7 7v2H5v12h14V9h-2Zm-8 0V7c0-1.66 1.34-3 3-3s3 1.34 3 3v2h-6Z"/>
                    </svg>
                    <input class="input" type="password" name="password" maxlength="100" required>
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <center>
                        <button type="submit" class="button is-primary is-rounded">Entrar</button>
                    </center>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
