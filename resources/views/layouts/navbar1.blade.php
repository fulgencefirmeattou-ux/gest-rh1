<div class="navbar-custom">
    <div class="topbar container-fluid">
        <div class="d-flex align-items-center gap-1">

            <!-- Topbar Brand Logo -->
            <div class="logo-topbar">
                <!-- Logo light -->
                <a href="index.html" class="logo-light">
                    <span class="logo-lg">
                        <img src="{{url('assets/images/logo.png')}}" alt="logo">
                    </span>
                    <span class="logo-sm">
                        <img src="{{ url('assets/images/logo-sm.png') }}" alt="small logo">
                    </span>
                </a>

                <!-- Logo Dark -->
                <a href="index.html" class="logo-dark">
                    <span class="logo-lg">
                        <img src="{{ url('assets/images/logo-dark.png') }}" alt="dark logo">
                    </span>
                    <span class="logo-sm">
                        <img src="{{ url('assets/images/logo-sm.png') }}" alt="small logo">
                    </span>
                </a>
            </div>

            <!-- Sidebar Menu Toggle Button -->
            <button class="button-toggle-menu">
                <i class="ri-menu-line"></i>
            </button>

            <!-- Horizontal Menu Toggle Button -->
            <button class="navbar-toggle" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <div class="lines">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
        </div>

        <ul class="topbar-menu d-flex align-items-center gap-3">
            {{-- ── Notifications dynamiques ── --}}
            @php
                $notifs = auth()->user()->unreadNotifications->take(8);
                $nbNotifs = auth()->user()->unreadNotifications->count();
            @endphp
            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button"
                    aria-haspopup="false" aria-expanded="false">
                    <i class="ri-notification-3-line fs-22"></i>
                    @if($nbNotifs > 0)
                        <span class="noti-icon-badge badge text-bg-pink">{{ $nbNotifs > 9 ? '9+' : $nbNotifs }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg py-0">
                    <div class="p-2 border-top-0 border-start-0 border-end-0 border-dashed border">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0 fs-16 fw-semibold">Notifications <span class="badge bg-secondary ms-1">{{ $nbNotifs }}</span></h6>
                            </div>
                            @if($nbNotifs > 0)
                            <div class="col-auto">
                                <form method="POST" action="{{ route('notifications.markAllRead') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 text-dark text-decoration-underline">
                                        <small>Tout marquer lu</small>
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div style="max-height: 320px;" data-simplebar>
                        @forelse($notifs as $notif)
                            @php $data = $notif->data; @endphp
                            <a href="{{ route('notifications.go', $notif->id) }}"
                               class="dropdown-item notify-item {{ $notif->read_at ? '' : 'unread-notif' }}">
                                <div class="notify-icon bg-{{ $data['couleur'] ?? 'primary' }}-subtle">
                                    <i class="{{ $data['icone'] ?? 'ri-notification-line' }} text-{{ $data['couleur'] ?? 'primary' }}"></i>
                                </div>
                                <p class="notify-details fw-semibold mb-0">{{ $data['titre'] ?? '' }}</p>
                                <small class="text-muted">{{ $data['message'] ?? '' }}</small>
                                <br><small class="noti-time text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                            </a>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="ri-notification-off-line fs-24"></i>
                                <p class="mb-0 mt-1 small">Aucune notification</p>
                            </div>
                        @endforelse
                    </div>

                    <a href="{{ route('notifications.index') }}"
                        class="dropdown-item text-center text-primary fw-bold notify-item border-top border-light py-2">
                        Voir toutes les notifications
                    </a>
                </div>
            </li>
            <li class="d-none d-sm-inline-block">
                <div class="nav-link" id="light-dark-mode">
                    <i class="ri-moon-line fs-22"></i>
                </div>
            </li>

            <li class="dropdown">
                <a class="nav-link dropdown-toggle arrow-none nav-user" data-bs-toggle="dropdown" href="#" role="button"
                    aria-haspopup="false" aria-expanded="false">
                    <span class="account-user-avatar">
                        {{-- <img src="{{ ('assets/images/users/avatar-1.jpg') }}" alt="user-image" width="32" class="rounded-circle"> --}}
                        {{-- @if(auth()->user() && auth()->user()->photo)
                            <img src="{{ asset(auth()->user()->photo) }}"
                                alt="Photo de profil"
                                width="30"
                                height="30"
                                style="border-radius: 50%; object-fit: cover;">
                        @else
                            <i class="ri-account-circle-line fs-18"></i>
                        @endif --}}

                    </span>
                    <span class="d-lg-block d-none">
                        <h5 class="my-0 fw-normal">{{ auth()->user()->login ?? '' }}<i
                                class="ri-arrow-down-s-line d-none d-sm-inline-block align-middle"></i></h5>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                    {{-- ── Welcome ── --}}
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>

                    {{-- ── Mon profil (tous) ── --}}
                    <a href="{{ route('employe.profil') }}" class="dropdown-item">
                        <i class="ri-account-circle-line fs-18 align-middle me-1"></i>
                        <span>Mon profil</span>
                    </a>
                        {{-- ── Se déconnecter (tous) ── --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center">
                            <i class="ri-logout-box-line fs-18 align-middle me-1"></i>
                            <span>Se déconnecter</span>
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>