@extends('admin.layouts.admin')

@section('title', trans('serverstatus::admin.settings.title'))

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('serverstatus.admin.settings') }}" method="POST">
                @csrf

                <h5>{{ trans('serverstatus::admin.settings.section_servers') }}</h5>

                <div class="mb-3">
                    <label class="form-label">{{ trans('serverstatus::admin.settings.monitored_servers') }}</label>

                    @forelse ($servers as $server)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="server_{{ $server->id }}" name="monitored_servers[]" value="{{ $server->id }}" @checked(in_array($server->id, $monitoredServerIds))>
                            <label class="form-check-label" for="server_{{ $server->id }}">{{ $server->name }}</label>
                        </div>
                    @empty
                        <div class="text-muted">{{ trans('serverstatus::admin.settings.no_servers') }}</div>
                    @endforelse

                    <div class="form-text">{{ trans('serverstatus::admin.settings.monitored_servers_help') }}</div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="uptime_window_days">{{ trans('serverstatus::admin.settings.uptime_window_days') }}</label>
                    <input type="number" min="1" max="365" class="form-control" id="uptime_window_days" name="uptime_window_days" value="{{ $uptimeWindowDays }}">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="intro_text">{{ trans('serverstatus::admin.settings.intro_text') }}</label>
                    <textarea class="form-control" id="intro_text" name="intro_text" rows="3">{{ $introText }}</textarea>
                </div>

                <hr>

                <h5>{{ trans('serverstatus::admin.settings.section_discord') }}</h5>

                <div class="mb-3">
                    <label class="form-label" for="discord_webhook">{{ trans('serverstatus::admin.settings.discord_webhook') }}</label>
                    <input class="form-control" id="discord_webhook" name="discord_webhook" value="{{ $discordWebhook }}" placeholder="https://discord.com/api/webhooks/...">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="discord_message_down">{{ trans('serverstatus::admin.settings.discord_message_down') }}</label>
                    <input class="form-control" id="discord_message_down" name="discord_message_down" value="{{ $discordMessageDown }}">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="discord_message_up">{{ trans('serverstatus::admin.settings.discord_message_up') }}</label>
                    <input class="form-control" id="discord_message_up" name="discord_message_up" value="{{ $discordMessageUp }}">
                    <div class="form-text">{{ trans('serverstatus::admin.settings.discord_message_help') }}</div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>

    @if ($servers->isNotEmpty())
        <div class="card shadow mb-4">
            <div class="card-header">{{ trans('serverstatus::admin.settings.badges_title') }}</div>
            <div class="card-body">
                <p class="text-muted">{{ trans('serverstatus::admin.settings.badges_help') }}</p>

                @foreach ($servers as $server)
                    <div class="mb-2">
                        <label class="form-label mb-1">{{ $server->name }}</label>
                        <input type="text" class="form-control" value="{{ route('serverstatus.badge', $server) }}" readonly="readonly" onclick="this.select()">
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection
