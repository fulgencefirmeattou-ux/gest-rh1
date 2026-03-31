<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Dashboard | Velonic - Bootstrap 5 Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully responsive admin theme which can be used to build CRM, CMS,ERP etc." name="description" />
    <meta content="Techzaa" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{url('assets/images/favicon.ico')}}">

    <!-- Daterangepicker css -->
    <link rel="stylesheet" href="{{url('assets/vendor/daterangepicker/daterangepicker.css')}}">

    <!-- Vector Map css -->
    <link rel="stylesheet" href="{{ url('assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}">

    <!-- Theme Config Js -->
    <script src="{{url('assets/js/config.js')}}"></script>

    <!-- App css -->
    <link href="{{url('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{url('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: start;
            align-items: start;
            background: #fff;
        }

        .badge {
            text-align: start;
            margin-bottom: 4px;
            width: 50%;
            padding: 4px;
            border: 1px solid black; 
            border-radius: 8px;
            padding: 24px;
        }

        .contenu {
            display: table;
            width: 100%;
        }

        .bloc {
            display: table-cell;
            vertical-align: middle;
        }

        .bloc.photo {
            width: 25%;
        }

        .avatar-md {
            height: 4.5rem;
            width: 4.5rem;
        }

        .bloc.info {
            width: 75%;
            padding-left: 10px;
        }

        /* .bloc.info h3, .bloc.info h4 {
            margin-bottom: 5px;
        } */

        .badge-code {
            margin-top: 50px !important;
        }
    </style>
</head>
<body>

<div class="container">
    <div class=" badge " >
        <span class="logo-sm ps-3">
            <img src="{{ public_path('assets/images/logo.png') }}" alt="small logo" style="width: 100px;">
        </span>
        <div class="contenu">
            <div class="bloc photo">
                @if($employe->photo)
                    <img src="{{ public_path($employe->photo) }}" class="avatar-md">
                @endif
            </div>
            <div class="bloc info">
                <h3 class="fs-18 mb-2 text-dark" style="margin-bottom: 5px;"> Nom: {{ ucfirst($employe->nom) }} {{ ucfirst($employe->prenom) ?? '_' }}</h3>
                <h4 class="fs-16 mb-2 text-dark" style="margin-bottom: 5px;">Matricule: {{ $employe->matricule }}</h4>
                <h4 class="fs-16 mb-2 text-dark" style="margin-bottom: 5px;">Poste: {{ $employe->poste ?? '—' }}</h4>

            </div>

        </div>
        <div class="badge-code">
            <span class="badge-code text-dark fs-16">
                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($employe->badge_code, 'C128') }}" alt="barcode">
                {{-- {{ $employes->badge_code }} --}}
            </span>
        </div>
    </div>
</div>

<!-- Vendor js -->
        {{-- <script src="{{url('assets/js/vendor.min.js')}}"></script>

        <!-- Daterangepicker js -->
        <script src="{{url('assets/vendor/daterangepicker/moment.min.js')}}"></script>
        <script src="{{url('assets/vendor/daterangepicker/daterangepicker.js')}}"></script>
        
        <!-- Apex Charts js -->
        <script src="{{url('assets/vendor/apexcharts/apexcharts.min.js')}}"></script>

        <!-- Vector Map js -->
        <script src="{{url('assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
        <script src="{{url('assets/vendor/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js')}}"></script>

        <!-- Dashboard App js -->
        <script src="{{url('assets/js/pages/dashboard.js')}}"></script>


        <!-- App js -->
        <script src="{{url('assets/js/app.min.js')}}"></script> --}}

</body>
</html>
