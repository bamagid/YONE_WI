<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identifiants de Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .colAuth {
            background-color: #222222ce;
            color: white;
            /* padding: 3vh; */
        }

        .colAuth .card-title {
            color: #F2743B;
            font-weight: bold;
        }

        .card {
            margin: 0 auto;
            border-radius: 2vh;
            /* height: 50vh; */
        }

        .card img {
            width: 100%;
            height: 100%;
        }

        .logo {
            max-width: 25vh !important;
            height: 8vh;
            /* border: 1px solid maroon; */
            /* margin: vh 0; */
        }

        .card .btnAuth {
            background-color: #F2743B;
            color: white;
            /* margin-top: 5vh; */
        }

        .require {
            color: #2CCED2;
        }
        .containerItemText{
            min-height: 60vh;
        }
    </style>
</head>

<body>
    <div class="containerConnexion  py-5">
        <div class="card cardNewUserCreated mb-3" style="max-width: 840px;">
            <div class="row containerGrid g-0">
                <div class="col-6 containerItem">
                    <!-- Remplacer src par le chemin de l'image -->
                    <img src="https://i.ibb.co/mNQ3kc8/auth1.png" class="img-fluid rounded-start" alt="...">
                </div>
                <div class="col-6 containerItemText colAuth rounded-end">
                    <div class="card-body">
                        <h4 class="card-title text-center">IDENTIFIANTS DE CONNEXION</h4>
                        <div class="text-center">
                            <img src="https://i.ibb.co/dJg1jcj/logo.png" alt="Logo yone_wi" class="logo">
                        </div>
                        <div class="">
                            <p class="fs-5"><strong>Nom d'utilisateur:</strong> {{ $username }}</p>
                            <p class="fs-5"><strong>Mot de passe:</strong> {{ $password }}</p>
                        </div>

                        <div class="text-center">
                            <p class="fs-5">Utilisez ces informations pour vous connecter à notre site.</p>
                            <p class="fs-5">Veuillez les personnaliser une fois que vous êtes connectés.</p>
                            <a href="http://localhost:4200/auth" style="color: white">Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>