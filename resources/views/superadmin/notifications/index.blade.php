@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<div class="container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="ri-notification-3-line me-2"></i>Notifications</h2>
        <form method="POST" action="{{ route('notifications.markAllRead') }}">
            @csrf
            <button class="btn btn-outline-secondary btn-sm">
                <i class="ri-check-double-line me-1"></i>Tout marquer lu
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            @forelse($notifications as $notif)
                @php
                    $data  = $notif->data;
                    $goUrl = route('notifications.go', $notif->id);
                @endphp
                <div class="d-flex align-items-center p-3 border-bottom {{ $notif->read_at ? '' : 'bg-light' }}">
                    <div class="me-3 flex-shrink-0">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-{{ $data['couleur'] ?? 'secondary' }}-subtle"
                             style="width:42px;height:42px;">
                            <i class="{{ $data['icone'] ?? 'ri-notification-line' }} text-{{ $data['couleur'] ?? 'secondary' }} fs-18"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold {{ $notif->read_at ? 'text-muted' : '' }}">{{ $data['titre'] ?? '' }}</span>
                            <small class="text-muted ms-3 text-nowrap">{{ $notif->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-0 small text-muted">{{ $data['message'] ?? '' }}</p>
                    </div>
                    <div class="ms-3 d-flex align-items-center gap-2 flex-shrink-0">
                        @if(!$notif->read_at)
                            <span class="badge bg-primary">Nouveau</span>
                        @endif
                        <a href="{{ $goUrl }}" class="btn btn-sm btn-primary">
                            <i class="ri-eye-line me-1"></i>Voir
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="ri-notification-off-line fs-36"></i>
                    <p class="mt-2">Aucune notification</p>
                </div>
            @endforelse
        </div>
        @if($notifications->hasPages())
            <div class="card-footer">{{ $notifications->links() }}</div>
        @endif
    </div>
</div>
@endsection
