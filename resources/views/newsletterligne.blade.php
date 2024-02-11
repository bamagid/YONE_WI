<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle ligne de bus ajoutée!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #F2743B;
        }

        .content {
            margin-bottom: 20px;
        }

        .content p {
            margin-bottom: 10px;
        }
        .logo {
            max-width: 100px;
            margin-bottom: 20px;
        }

        .footer {
            text-align: center;
            color: #999;
        }

        .footer p {
            margin: 0;
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
        <div class="header">
            <h1>Nouvelle ligne de bus ajoutée!</h1>
        </div>
        <div class="text-center">
            <img src="https://i.ibb.co/dJg1jcj/logo.png" alt="Logo Yone_Wi" class="logo">
        </div>
        <div class="content">
            <p>Bonjour,</p>
            <p>Nous sommes heureux de vous annoncer qu'une nouvelle ligne de bus avec le numero {{$ligne->nom}} a été ajoutée au reseau {{$ligne->reseau->nom}}</p>
            <p>Cette nouvelle ligne offrira plus d'options pour vos déplacements et contribuera à améliorer l'accessibilité de notre réseau.</p>
            <p>Nous vous encourageons à consulter notre site pour plus de détails sur cette nouvelle ligne et les horaires disponibles.</p>
            <a href="http://localhost:4200/lignes" style="color: #fff">Voir les lignes</a>
            <p>Si vous souhaitez vous désabonner de notre newsletter, veuillez cliquer sur le bouton ci-dessous :</p>
            <form action="http://127.0.0.1:8000/api/newsletter/unscribe" method="POST">
                <input type="hidden" name="email" value="{{ $user->email }}">
                <button type="submit" class="btn-unsubscribe">Se désabonner</button>
            </form>
            <p>Merci de votre confiance et de votre fidélité à notre service.</p>
            <p>Cordialement,<br>L'équipe de support de Yone_Wi</p>
        </div>
    </div>
</body>
</html>
