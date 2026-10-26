@extends('layouts.app')

@section('title', trans('serverstatus::messages.title'))

@section('content')
    <style>
        .serverstatus-bars {
            display: flex;
            gap: 2px;
            overflow-x: auto;
            padding-bottom: 2px;
        }

        .serverstatus-bar {
            flex: 1 0 3px;
            min-width: 3px;
            height: 22px;
            border-radius: 2px;
        }

        .serverstatus-bar-operational { background-color: var(--bs-success, #198754); }
        .serverstatus-bar-partial { background-color: var(--bs-warning, #ffc107); }
        .serverstatus-bar-major { background-color: var(--bs-danger, #dc3545); }
        .serverstatus-bar-nodata { background-color: var(--bs-secondary-bg, #e9ecef); }

        .serverstatus-sparkline {
            width: 100%;
            height: 32px;
            color: var(--bs-primary, #0d6efd);
        }
    </style>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <h1 class="mb-3">{{ trans('serverstatus::messages.title') }}</h1>

            @if (! empty($introText))
                <div class="alert alert-secondary" style="white-space: pre-line;">{{ $introText }}</div>
            @endif

            @if ($hasOngoingIncident)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> {{ trans('serverstatus::messages.banner.incident') }}
                </div>
            @else
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> {{ trans('serverstatus::messages.banner.operational') }}
                </div>
            @endif

            @if ($upcomingMaintenances->isNotEmpty())
                <div class="card shadow mb-4">
                    <div class="card-header">{{ trans('serverstatus::messages.incidents.upcoming_title') }}</div>
                    <ul class="list-group list-group-flush">
                        @foreach ($upcomingMaintenances as $incident)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <span class="badge bg-info">{{ trans('serverstatus::messages.incidents.upcoming') }}</span>
                                        <span class="badge bg-{{ \Azuriom\Plugin\ServerStatus\Models\StatusIncident::categoryColor($incident->categoryOrDefault()) }}">
                                            {{ trans('serverstatus::messages.incidents.category_'.$incident->categoryOrDefault()) }}
                                        </span>
                                        <strong class="ms-1">
                                            {{ $incident->title ?? trans('serverstatus::messages.incidents.category_'.$incident->categoryOrDefault().'_default', ['server' => $incident->server?->name ?? trans('serverstatus::messages.incidents.all_servers')]) }}
                                        </strong>
                                    </div>
                                    <span class="text-muted small">{{ format_date($incident->started_at, true) }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow mb-4">
                <div class="card-header">{{ trans('serverstatus::messages.servers.title') }}</div>
                <ul class="list-group list-group-flush">
                    @forelse ($statusServers as $data)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $data['server']->name }}</strong>
                                    <span class="badge bg-{{ $data['online'] ? 'success' : 'danger' }} ms-2">
                                        {{ trans('serverstatus::messages.servers.'.($data['online'] ? 'online' : 'offline')) }}
                                    </span>
                                </div>
                                <div class="text-muted small text-end">
                                    @if ($data['online'])
                                        {{ trans('serverstatus::messages.servers.players', ['online' => $data['players'], 'max' => $data['maxPlayers']]) }}
                                        &middot;
                                    @endif
                                    {{ trans('serverstatus::messages.servers.uptime', ['percent' => $data['uptime'], 'days' => $windowDays]) }}
                                </div>
                            </div>

                            <div class="serverstatus-bars mt-2">
                                @foreach ($data['dailyStatus'] as $day)
                                    <div
                                        class="serverstatus-bar serverstatus-bar-{{ $day['status'] }}"
                                        title="{{ $day['date']->translatedFormat('d F Y') }} — {{ trans('serverstatus::messages.servers.day_'.$day['status']) }}{{ $day['downtimePercent'] > 0 ? ' ('.$day['downtimePercent'].'%)' : '' }}"
                                    ></div>
                                @endforeach
                            </div>

                            @if ($data['playerHistory'] !== null)
                                <svg
                                    class="serverstatus-sparkline mt-1"
                                    viewBox="0 0 100 30"
                                    preserveAspectRatio="none"
                                    role="img"
                                    aria-label="{{ trans('serverstatus::messages.servers.player_history', ['hours' => $playerHistoryHours]) }}"
                                >
                                    <polyline
                                        points="{{ $data['playerHistory']['points'] }}"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        vector-effect="non-scaling-stroke"
                                    ></polyline>
                                </svg>
                            @endif
                        </li>
                    @empty
                        <li class="list-group-item">{{ trans('serverstatus::messages.servers.none') }}</li>
                    @endforelse
                </ul>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header">{{ trans('serverstatus::messages.incidents.title') }}</div>
                <ul class="list-group list-group-flush">
                    @forelse ($incidents as $incident)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="badge bg-{{ $incident->isOngoing() ? 'danger' : 'secondary' }}">
                                        {{ trans('serverstatus::messages.incidents.'.($incident->isOngoing() ? 'ongoing' : 'resolved')) }}
                                    </span>
                                    <span class="badge bg-{{ \Azuriom\Plugin\ServerStatus\Models\StatusIncident::categoryColor($incident->categoryOrDefault()) }}">
                                        {{ trans('serverstatus::messages.incidents.category_'.$incident->categoryOrDefault()) }}
                                    </span>
                                    <strong class="ms-1">
                                        {{ $incident->title ?? trans('serverstatus::messages.incidents.category_'.$incident->categoryOrDefault().'_default', ['server' => $incident->server?->name ?? trans('serverstatus::messages.incidents.all_servers')]) }}
                                    </strong>
                                </div>
                                <span class="text-muted small">{{ format_date($incident->started_at) }}</span>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item">{{ trans('serverstatus::messages.incidents.none') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
