@extends('layouts.app')
@section('title', 'Notification')

@section('content')
<div class="container-fluid">

    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="page-title mb-0">Notification</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('notifications.index') }}">Notifications</a></li>
                    <li class="breadcrumb-item active">Détail</li>
                </ol>
            </div>
        </div>
    </div>

    @php
        $data    = $notif->data;
        $couleur = $data['couleur'] ?? 'secondary';
        $icone   = $data['icone']   ?? 'ri-notification-line';
        $titre   = $data['titre']   ?? 'Notification';
        $message = $data['message'] ?? '';
        $raison  = $data['raison']  ?? null;
        $motif   = $data['motif']  ?? null;
    @endphp

    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">

                    {{-- Icône --}}
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-{{ $couleur }}-subtle mb-4"
                         style="width:72px;height:72px;">
                        <i class="{{ $icone }} text-{{ $couleur }} fs-1"></i>
                    </div>

                    {{-- Contenu --}}
                    <h5 class="fw-bold mb-2">{{ $titre }}</h5>
                    <p class="text-muted mb-1">{{ $message }}</p>

                    @if($raison || $motif)
                    <div class="text-start d-inline-block mb-3" style="max-width:460px;">
                        @if($raison)
                        <div class="alert alert-light border px-3 py-2 mb-2">
                            <span class="small text-muted fw-semibold d-block mb-1">
                                <i class="ri-file-text-line me-1"></i>Motif du congé
                            </span>
                            <span class="small">{{ $raison }}</span>
                        </div>
                        @endif
                        @if($motif)
                        <div class="alert alert-light border px-3 py-2 mb-0">
                            <span class="small text-muted fw-semibold d-block mb-1">
                                <i class="ri-chat-quote-line me-1"></i>Commentaire RH
                            </span>
                            <span class="small">{{ $motif }}</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    <small class="text-muted d-block mb-4">
                        <i class="ri-time-line me-1"></i>{{ $notif->created_at->diffForHumans() }}
                    </small>

                    {{-- Actions --}}
                    @if($url)
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-arrow-left-line me-1"></i>Retour
                            </a>
                            <a href="{{ $url }}" class="btn btn-primary">
                                <i class="ri-external-link-line me-1"></i>Accéder
                            </a>
                        </div>
                    @else
                        <a href="{{ route('notifications.index') }}" class="btn btn-primary">
                            <i class="ri-arrow-left-line me-1"></i>Retour aux notifications
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </div>

</div>
@endsection

