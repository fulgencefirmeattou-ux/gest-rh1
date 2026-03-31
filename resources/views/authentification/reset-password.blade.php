<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser le mot de passe</title>
</head>
<body>
   
</body>
</html>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Connexion | GRH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully responsive admin theme which can be used to build CRM, CMS,ERP etc." name="description" />
    <meta content="Techzaa" name="author" />

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
                <div class="col-xxl-8 col-lg-10">
                    <div class="card overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-6 d-none d-lg-block p-2">
                                <img src="{{ url('assets/images/rh.jpg') }}" alt="" class="img-fluid rounded h-100">
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex flex-column h-100">
                                    <div class="auth-brand p-4">
                                        <a href="index.html" class="logo-light">
                                            <img src="assets/images/logo.png" alt="logo" height="22">
                                        </a>
                                        <a href="index.html" class="logo-dark">
                                            <img src="{{ url('assets/images/logo-dark.png') }}" alt="dark logo" height="22">
                                        </a>
                                    </div>
                                    <div class="p-4 my-auto">
                                        @if (session('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                        <h4 class="mb-2">Réinitialisation du mot de passe</h4>

                                        @if (session('status'))
                                            <p style="color: green;">{{ session('status') }}</p>
                                        @endif

                                        <form method="POST" action="{{ route('password.update') }}">
                                            @csrf
                                            <input type="hidden" name="token" value="{{ $token }}">
                                            <div class="mb-3">
                                                <div class="mb-1">
                                                    <label for="emailaddress" class="form-label text-dark">Votre Email</label>
                                                    <input class="form-control @error('email') is-invalid @enderror" name="email" type="email" name="email" value="{{ request()->email ?? old('email') }}" required=""
                                                        placeholder="Enter your email">
                                                </div>
                                                @error('email')
                                                    <div class="text-danger small mb-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <div class="mb-1">
                                                    <label for="password" class="form-label text-dark">Mot de passe</label>
                                                    <input class="form-control @error('password') is-invalid @enderror" name="password" type="password" required="" id="password"
                                                        placeholder="Enter your password" >
                                                </div>
                                                @error('password')
                                                    <div class="text-danger small mb-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <div class="mb-1">
                                                    <label for="password" class="form-label text-dark">Confirmation de mot de passe</label>
                                                    <input class="form-control @error('password') is-invalid @enderror" name="password_confirmation" type="password" required="" id="password"
                                                        placeholder="Enter your password" >
                                                </div>
                                                @error('password')
                                                    <div class="text-danger small mb-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <button class="btn btn-soft-primary w-100" type="submit">Réinitialiser</button>
                                        </form>


                                        @if ($errors->any())
                                            <ul style="color:red;">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            {{-- <div class="row">
                <div class="col-12 text-center">
                    <p class="text-dark-emphasis">Don't have an account? <a href="auth-register.html"
                            class="text-dark fw-bold ms-1 link-offset-3 text-decoration-underline"><b>Sign up</b></a>
                    </p>
                </div> <!-- end col -->
            </div> --}}
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt fw-medium">
        <span class="text-dark">
            <script>document.write(new Date().getFullYear())</script> © Velonic - Theme by Techzaa
        </span>
    </footer>
    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>