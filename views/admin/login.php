<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión — <?= htmlspecialchars(SITE_NAME) ?></title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⛵</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Source Sans 3', sans-serif; background: #f0f2f5;
            display: flex; align-items: center; justify-content: center; min-height: 100vh;
        }
        .login-box {
            background: #fff; border-radius: 12px; padding: 2.5rem; width: 100%; max-width: 400px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .login-box h1 {
            text-align: center; font-size: 1.5rem; color: <?= COLOR_PRIMARY ?>; margin-bottom: 0.3rem;
        }
        .login-box .subtitle {
            text-align: center; color: #888; font-size: 0.85rem; margin-bottom: 1.5rem;
        }
        .form-group { margin-bottom: 1.2rem; }
        .form-group label { display: block; margin-bottom: 0.3rem; font-weight: 600; font-size: 0.9rem; }
        .form-group input {
            width: 100%; padding: 0.7rem 0.9rem; border: 2px solid #dee2e6;
            border-radius: 6px; font-size: 1rem; font-family: inherit;
        }
        .form-group input:focus { border-color: <?= COLOR_PRIMARY ?>; outline: none; }
        .btn {
            display: block; width: 100%; padding: 0.75rem; border: none; border-radius: 6px;
            background: <?= COLOR_PRIMARY ?>; color: #fff; font-size: 1rem; font-weight: 600;
            cursor: pointer; transition: opacity 0.2s;
        }
        .btn:hover { opacity: 0.85; }
        .error {
            background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;
            padding: 0.7rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.9rem;
        }
        .back-link { display: block; text-align: center; margin-top: 1.2rem; color: #888; font-size: 0.85rem; }
        .back-link a { color: <?= COLOR_PRIMARY ?>; }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>⛵ <?= htmlspecialchars(SITE_NAME) ?></h1>
        <p class="subtitle">Panel de Administración</p>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="<?= SITE_URL ?>/admin/login" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">Iniciar sesión</button>
        </form>

        <p class="back-link"><a href="<?= SITE_URL ?>/">← Volver al sitio</a></p>
    </div>
</body>
</html>
