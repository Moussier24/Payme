<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Échoué</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 90%;
            width: 500px;
        }
        .error-icon {
            color: #dc3545;
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        h1 {
            color: #dc3545;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        p {
            color: #6c757d;
            font-size: 1.1rem;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-icon">✕</div>
        <h1>Paiement Échoué</h1>
        <p>Désolé, votre paiement n'a pas pu être traité. Veuillez réessayer plus tard.</p>
    </div>
</body>
</html> 