<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <style>
        .colAuth {
            background-color: #222222ce;
            color: white;
            padding: 3vh;
        }

        .colAuth h3 {
            color: #F2743B;
            font-weight: bold;
        }

        .card {
            margin: 0 auto;
            border: none;
            border-radius: 2vh;
        }

        .card img {
            width: 100%;
            height: 100%;
        }

        .logo {
            max-width: 25vh !important;
            height: 8vh;
            margin: 5vh 0;
        }

        .card .btnAuth {
            background-color: #F2743B;
            color: white;
            margin-top: 5vh;
        }

        .require {
            color: #2CCED2;
        }

        .containerReinitialiser {
            min-height: 80vh;
        }

        .containerReinitialiser .card img {
            max-height: 60vh;
        }
    </style>
</head>

<body>
    <div class="containerConnexion  py-5">
        <div class="card mb-3" style="max-width: 840px;">
            <div class="row g-0">
                <div class="col-md-6">
                    <img src="{{asset('auth1.png')}}" class="img-fluid rounded-start" alt="...">
                </div>
                <div class="col-md-6 colAuth rounded-end">
                    <div class="card-body">
                        <h3 class="card-title text-center">REINITIALISER</h3>
                        <div class="text-center">
                            <img src="{{ asset('yonewilogo.png')}}" alt="Logo yone_wi" class="logo">
                        </div>

                        <form action="{{ route('reset.password.post') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="mb-4">
                                <label for="password" class="form-label fw-bold fs-5">Nouveau mot de passe: <span
                                        class="require">*</span></label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Votre nouveau mot de passe...">
                            </div>
                            <div class="mb-5">
                                <label for="passwordconfirm" class="form-label fw-bold fs-5">Confirmation mot de passe:
                                    <span class="require">*</span></label>
                                <input type="password" class="form-control" id="passwordconfirm"
                                    name="password_confirmation" placeholder="Votre mot de passe à nouveau ...">
                            </div>

                            <button class="btn btnAuth col-12 fw-bold fs-5 d-block"
                                type="submit">Réinitialiser</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
