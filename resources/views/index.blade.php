@extends('layouts.app')

@section('title', trans('serverstatus::messages.title'))

@section('content')
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
                                <div class="text-muted small">
                                    @if ($data['online'])
                                        {{ trans('serverstatus::messages.servers.players', ['online' => $data['players'], 'max' => $data['maxPlayers']]) }}
                                        &middot;
                                    @endif
                                    {{ trans('serverstatus::messages.servers.uptime', ['percent' => $data['uptime'], 'days' => $windowDays]) }}
                                </div>
                            </div>
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
                                    <strong class="ms-1">
                                        {{ $incident->title ?? trans('serverstatus::messages.incidents.'.$incident->type.'_default', ['server' => $incident->server?->name ?? trans('serverstatus::messages.incidents.all_servers')]) }}
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
