<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification de déblocage de compte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .container-notification {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            max-width: 600px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            max-width: 100px;
            margin-bottom: 20px;
        }
        .btn {
            background-color: #F2743B;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container-notification">
        <div class="card">
            <div class="text-center">
                <img src="https://i.ibb.co/dJg1jcj/logo.png" alt="Logo Yone_Wi" class="logo">
            </div>
            <h2 class="text-center">Notification de déblocage de compte</h2>
            <p>Cher/Chère {{$user->prenom}} {{ $user->nom}},</p>
            <p>Nous sommes heureux de vous informer que votre compte sur Yone_Wi a été débloqué avec succès.</p>
            <p>Vous pouvez maintenant accéder à toutes les fonctionnalités de votre compte.</p>
            <p>Si vous avez des questions ou des préoccupations, n'hésitez pas à nous contacter à l'adresse suivante bamagid60@gmail.com .</p>
            <p>Nous vous remercions pour votre compréhension et votre coopération.</p>
            <p>Cordialement,<br>L'équipe de support de Yone_Wi</p>
    </div>
</body>

</html>