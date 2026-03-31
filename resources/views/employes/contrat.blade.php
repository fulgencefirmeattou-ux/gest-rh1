@extends('layouts.base')
@section('title', 'document administratif')
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="profile-bg-picture"
                    style="background-image:url({{ url('assets/images/bg-profile.jpg') }})">
                    <span class="picture-bg-overlay"></span>
                    <!-- overlay -->
                </div>
                <!-- meta -->
                <div class="profile-user-box">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="profile-user-img"><img src="{{ asset ( $employes->photo )}}" alt=""
                                    class="avatar-lg rounded-circle"></div>
                            <div class="">
                                <h4 class="mt-4 fs-17 ellipsis">{{ ucfirst($employes->nom) }} {{ ucfirst($employes->prenom) ?? '_' }}</h4>
                                <p class="font-13"> {{ $employes->poste ?? '—' }}</p>
                                {{-- <p class="text-muted mb-0"><small>California, United States</small></p>--}}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                @if ($contrat)
                                    <a href="{{ route('contrats.download', $contrat->id) }}"
                                    target="_blank" class="btn btn-primary">
                                        Télécharger mon contrat
                                    </a>
                                @else
                                    <span class="text-muted">
                                        Aucun contrat disponible
                                    </span>
                                @endif
                                <a class="btn btn-soft-info" href="{{ route('badge.pdf', $employes->id) }}"> <i class="ri-check-double-fill fs-18 me-1 lh-1"></i>Mon badge</a>
                            </div>
                        </div> 
                    </div>
                </div> 
                <!--/ meta -->
            </div>
        </div> 
        <!-- end row -->

        <div class="row mt-4">
            <div class="col-sm-12">
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
                <div class="card p-0">
                    <div class="card-body p-0">
                        <div class="profile-content">
                            <ul class="nav nav-underline nav-justified gap-0">
                                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#aboutme" type="button" role="tab"
                                        aria-controls="home" aria-selected="true" href="#aboutme">Mes informations</a>
                                </li>
                                {{-- <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#user-activities" type="button" role="tab"
                                        aria-controls="home" aria-selected="true"
                                        href="#user-activities">Activities</a></li> --}}
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#edit-profile" type="button" role="tab"
                                        aria-controls="home" aria-selected="true"
                                        href="#edit-profile">Mon badge</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#projects" type="button" role="tab"
                                        aria-controls="home" aria-selected="true"
                                        href="#projects">Mes documents</a></li>
                            </ul>

                            <div class="tab-content m-0 p-4">
                                <div class="tab-pane active" id="aboutme" role="tabpanel"
                                    aria-labelledby="home-tab" tabindex="0">
                                    <div class="profile-desk">
                                        <h5 class="text-uppercase fs-16 ">Nom et prenom : <b class="text-dark fs-16 lowercase">{{ ucfirst($employes->nom) }} {{ ucfirst($employes->prenom) ?? '_' }} </b></h5> <br>
                                        <div class="designation mb-4"> 
                                            <h5 class="text-uppercase fs-16 ">Email : <b class="text-dark text-lowercase fs-16"> {{ ($employes->email) ?? '_' }} </b></h5><br>
                                            <h5 class="text-uppercase fs-16 ">Téléphone : <b class="text-dark text-lowercase fs-16"> {{ ($employes->phone) ?? '_' }} </b></h5><br>
                                            <h5 class="text-uppercase fs-16 ">Departement : <b class="text-dark text-lowercase fs-16"> {{ ($employes->service->departement->nom ?? '_') }} </b></h5><br>
                                            <p class="text-muted fs-16">
                                           {{-- {{ $employes->service->departement->description ?? '_'}} --}}
                                        </p>
                                        </div>
                                        

                                        <h5 class="mt-4 fs-17 text-dark">Information</h5>
                                        <table class="table table-condensed mb-0 border-top">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Service</th>
                                                    <td>
                                                        {{ $employes->service->nom ?? '_' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Poste</th>
                                                    <td>
                                                        {{ $employes->poste ?? '_' }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th scope="row">Type de contrat</th>
                                                    <td class="ng-binding">{{ $employes->contratActif->type_contrat  ?? '_' }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Date d'embauche</th>
                                                    <td>
                                                        {{ $employes->date_embauche ? $employes->date_embauche->format('d/m/Y') : '-' }}
                                                    </td>
                                                </tr>
                                                {{-- <tr>
                                                    <th scope="row">Date de debut</th>
                                                    <td>
                                                        {{ $employes->date_debut ? $employes->date_debut->format('d/m/Y') : '-' }}
                                                    </td>
                                                </tr> --}}
                                                <tr>
                                                    <th scope="row">Date de fin</th>
                                                    <td>
                                                        {{ $employes->date_fin ? $employes->date_fin->format('d/m/Y') : '-' }}
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div> <!-- end profile-desk -->
                                </div> <!-- about-me --> 


                                <!-- Informations personnelles -->
                                <div id="edit-profile" class="tab-pane">
                                    <div class="user-profile-content">
                                        <div class="text-start mb-4 w-50 p-4" style="border: 1px solid black; border-radius: 8px;">
                                            <span class="logo-sm ps-3">
                                                <img src="{{ url('assets/images/logo.png') }}" alt="small logo">
                                            </span>
                                            <div class="badge d-flex justify-betweent align-items-center gap-3 p-3">
                                                <div class="badge-img ">
                                                    <img src="{{ asset ( $employes->photo )}}" alt="" class="avatar-md">
                                                </div>
                                                <div class="text-start ">
                                                    <h3 class="fs-18 mb-2 text-dark"> Nom: {{ ucfirst($employes->nom) }} {{ ucfirst($employes->prenom) ?? '_' }}</h3>
                                                    <h4 class="fs-16 mb-2 text-dark">Matricule: {{ $employes->matricule }}</h4>
                                                    <h4 class="fs-16 mb-2 text-dark">Poste: {{ $employes->poste ?? '—' }}</h4>
                                                    
                                                </div>
                                                
                                                

                                            </div>
                                            <div class="ps-3">
                                                <span class="badge-code text-dark fs-16">
                                                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($employes->badge_code, 'C128') }}" alt="barcode">
                                                    {{-- {{ $employes->badge_code }} --}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- profile -->
                                <div id="projects" class="tab-pane">
                                    <div class="row m-t-10">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Project Name</th>
                                                            <th>Start Date</th>
                                                            <th>Due Date</th>
                                                            <th>Status</th>
                                                            <th>Assign</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Velonic Admin</td>
                                                            <td>01/01/2015</td>
                                                            <td>07/05/2015</td>
                                                            <td><span class="badge bg-info">Work
                                                                    in Progress</span></td>
                                                            <td>Techzaa</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>Velonic Frontend</td>
                                                            <td>01/01/2015</td>
                                                            <td>07/05/2015</td>
                                                            <td><span
                                                                    class="badge bg-success">Pending</span>
                                                            </td>
                                                            <td>Techzaa</td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>Velonic Admin</td>
                                                            <td>01/01/2015</td>
                                                            <td>07/05/2015</td>
                                                            <td><span class="badge bg-pink">Done</span>
                                                            </td>
                                                            <td>Techzaa</td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>Velonic Frontend</td>
                                                            <td>01/01/2015</td>
                                                            <td>07/05/2015</td>
                                                            <td><span class="badge bg-purple">Work
                                                                    in Progress</span></td>
                                                            <td>Techzaa</td>
                                                        </tr>
                                                        <tr>
                                                            <td>5</td>
                                                            <td>Velonic Admin</td>
                                                            <td>01/01/2015</td>
                                                            <td>07/05/2015</td>
                                                            <td><span class="badge bg-warning">Coming
                                                                    soon</span></td>
                                                            <td>Techzaa</td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

    </div>




@endsection







@section('content')
<div class="container">
    <h2 class="mt-3">Mon contrat</h2>
    <div class="ca">

    </div>
@if(!$contrat) <p>Pas de contrat actif.</p> @else
<p>Type: {{ $contrat->type_contrat }}</p>
<p>Période: {{ $contrat->date_debut->format('d/m/Y') }}
@if($contrat->date_fin) - {{ $contrat->date_fin->format('d/m/Y') }}
@endif</p>
<p>Salaire: {{ number_format($contrat->salaire_base,2) }} FCFA</p>
@if($contrat->pdf_path)
<a href="{{ asset('storage/'.$contrat->pdf_path) }}"
target="_blank" class="btn btn-primary">Télécharger le PDF</a>
@else
<a href="#" class="btn btn-secondary">PDF non disponible</a>
@endif
@endif
</div>
@endsection