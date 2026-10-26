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
        <span class="text-muted small text-end">
            {{ format_date($incident->started_at) }}
            @if ($incident->durationForHumans() !== null)
                <br>{{ trans('serverstatus::messages.incidents.'.($incident->isOngoing() ? 'duration_ongoing' : 'duration_resolved'), ['duration' => $incident->durationForHumans()]) }}
            @endif
        </span>
    </div>
</li>
