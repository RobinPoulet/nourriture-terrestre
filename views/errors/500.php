<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Erreur 500 - Problème serveur</title>
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        h1 {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }

        p {
            font-size: 1.25rem;
            max-width: 600px;
        }

        .button {
            margin-top: 2rem;
            padding: 0.75rem 1.5rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 3rem;
            }

            p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
<h1>500</h1>
<p>Une erreur interne s'est produite. Nos équipes ont été alertées. Vous pouvez réessayer plus tard.</p>
<a href="/" class="button">Retour à l'accueil</a>
</body>
</html>
