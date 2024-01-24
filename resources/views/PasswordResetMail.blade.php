<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du Mot de Passe</title>
</head>

<body style="font-family: 'Arial', sans-serif; background-color: #f4f4f4; padding: 20px;">
    <img src="https://i.ibb.co/dJg1jcj/logo.png" alt="Logo de l'entreprise" style="max-width: 150px;">
    <div
        style="background-color: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 400px; margin: auto; text-align: center; padding: 20px;">

        <h2 style="color: #333;">Réinitialisation du Mot de Passe</h2>

        <p style="color: #555; margin-bottom: 20px;">Vous avez demandé une réinitialisation du mot de passe. Cliquez sur
            le lien ci-dessous pour procéder :</p><br>
        <a href="reset-password/{{ $token }}"
            style="display: inline-block; background-color: #4caf50; color: #fff; padding: 10px 15px;
            text-decoration: none; border-radius: 4px;">Réinitialiser
            le Mot de Passe</a>
        <p style="color: #555; margin-top: 20px;">Si vous n'avez pas demandé cette réinitialisation, veuillez ignorer
            cet e-mail.</p>

    </div>

</body>

</html>
