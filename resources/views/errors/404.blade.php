<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>404 - Page introuvable | GRH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ url('assets/images/favicon.ico') }}">

    <!-- Theme Config Js -->
    <script src="{{ url('assets/js/config.js') }}"></script>

    <!-- App css -->
    <link href="{{ url('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{ url('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg position-relative">
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-5 col-lg-6 col-md-8">
                    <div class="card overflow-hidden text-center">
                        <div class="card-body p-5">
                            <div class="mb-4">
                                <span class="display-1 fw-bold text-warning">404</span>
                            </div>
                            <div class="mb-3">
                                <i class="ri-map-pin-off-line" style="font-size: 4rem; color: #f0ad4e;"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-2">Page introuvable</h3>
                            <p class="text-muted mb-4">
                                La page que vous recherchez n'existe pas ou a été déplacée.
                                Vérifiez l'adresse ou revenez à l'accueil.
                            </p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ url()->previous() }}" class="btn btn-soft-secondary">
                                    <i class="ri-arrow-left-line me-1"></i> Retour
                                </a>
                                <a href="{{ route('pointages.index') }}" class="btn btn-soft-primary">
                                    <i class="ri-home-4-line me-1"></i> Tableau de bord
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer footer-alt fw-medium">
        <span class="text-dark">
            Firme informatique <script>document.write(new Date().getFullYear())</script> © Firme Attou Co
        </span>
    </footer>

    <!-- Vendor js -->
    <script src="{{ url('assets/js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ url('assets/js/app.min.js') }}"></script>
</body>

</html>
