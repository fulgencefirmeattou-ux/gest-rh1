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
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        @if (session('status'))
                                            <div class="alert alert-success">
                                                <p style="color: green;">{{ session('status') }}</p>
                                            </div>
                                        @endif
                                        <h4 class="fs-20 text-dark">Connexion</h4>
                                        <p class="text-muted mb-3">Entrer votre addresse email et votre mot de passe pour accéder à votre compte</p>
                                        

                                        <!-- form -->
                                        <form action="{{ route('login') }}" method="POST">
                                            @csrf

                                            <div class="mb-3">
                                                <div class="mb-1">
                                                    <label for="emailaddress" class="form-label text-dark">Votre Email ou matricule</label>
                                                    {{-- <input class="form-control @error('email') is-invalid @enderror" name="email" type="email" id="emailaddress"  value="{{ old('email') }}" required=""
                                                        placeholder="Enter your email"> --}}
                                                    <input class="form-control @error('login') is-invalid @enderror" name="login" type="text" placeholder="Email ou Login" id="emailaddress"  value="{{ old('login') }}">

                                                </div>
                                                @error('email')
                                                    <div class="text-danger small mb-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                           
                                            
                                            <div class="mb-3">
                                                <div class="mb-1">
                                                    <a href="{{ route('password.request') }}" class="text-primary-emphasis float-end"><small>Mot de passe oublié ?</small></a>
                                                    <label for="password" class="form-label text-dark">Mot de passe</label>
                                                    <input class="form-control @error('password') is-invalid @enderror" name="password" type="password" required="" id="password"
                                                        placeholder="Enter your password" >
                                                </div>
                                                @error('password')
                                                    <div class="text-danger small mb-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                    
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="checkbox-signin">
                                                    <label class="form-check-label text-dark" for="checkbox-signin">Se rappeler de moi</label>
                                                </div>
                                            </div>
                                            <div class="mb-0 text-start">
                                                <button class="btn btn-soft-primary w-100" type="submit"><i
                                                        class="ri-login-circle-fill me-1"></i> <span class="fw-bold">Connexion</span> </button>
                                            </div>

                                            <div class="text-center mt-4">
                                                <p class="text-dark-emphasis">Vous n'avez pas de compte? <a href="{{ route('contact_admin') }}"
                                                        class="text-primary-emphasis fw-bold ms-1 link-offset-3 text-decoration-underline"><b>Contactez votre administrateur.</b></a>
                                                </p>
                                               
                                            </div>
                                        </form>
                                        <!-- end form-->
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