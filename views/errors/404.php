<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1B4965;
            --secondary: #2D6A4F;
            --accent: #E9C46A;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0D1B2A, var(--primary));
            color: #fff;
            text-align: center;
            padding: 2rem;
        }
        .wrap { max-width: 480px; }
        .code {
            font-size: 8rem;
            font-weight: 700;
            line-height: 1;
            background: linear-gradient(135deg, var(--accent), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        h1 { font-size: 1.5rem; margin: 1rem 0 .75rem; font-weight: 600; }
        p { color: rgba(255,255,255,.7); margin-bottom: 2rem; line-height: 1.6; }
        a {
            display: inline-block;
            padding: .75rem 2rem;
            background: var(--accent);
            color: #0D1B2A;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: opacity .2s;
        }
        a:hover { opacity: .9; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="code">404</div>
        <h1>Página no encontrada</h1>
        <p>Lo sentimos, la página que buscas no existe o fue movida.</p>
        <a href="/">Volver al inicio</a>
    </div>
</body>
</html>
