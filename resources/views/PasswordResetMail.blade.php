<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du Mot de Passe</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: auto;
            text-align: center;
            padding: 20px;
        }

        img {
            max-width: 150px;
            margin-bottom: 20px;
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            color: #555;
            margin-bottom: 20px;
            font-size: 16px;
        }

        a {
            display: inline-block;
            background-color: #F2743B;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="https://i.ibb.co/dJg1jcj/logo.png" alt="Logo de yone_wi">
        <h2>Réinitialisation du Mot de Passe</h2>
        <p>Vous avez demandé une réinitialisation du mot de passe. Cliquez sur
            le lien ci-dessous pour procéder :</p><br>
        <a href="http://127.0.0.1:8000/api/reset-password/{{ $token }} " style="color: white">Réinitialiser le
            Mot de
            Passe</a>
        <p>Si vous n'avez pas demandé cette réinitialisation, veuillez ignorer cet e-mail.</p>
    </div>
</body>

</html>
