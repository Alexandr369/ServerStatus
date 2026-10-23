@php
    $category = $incident->categoryOrDefault();
    $color = \Azuriom\Plugin\ServerStatus\Models\StatusIncident::categoryColor($category);
    $label = $incident->title ?? trans('serverstatus::messages.incidents.category_'.$category.'_default', [
        'server' => $incident->server?->name ?? trans('serverstatus::messages.incidents.all_servers'),
    ]);
@endphp
<div class="modal fade" id="serverstatus-notice-modal" data-incident-id="{{ $incident->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-{{ $color }} text-white">
                <h5 class="modal-title">{{ trans('serverstatus::messages.incidents.category_'.$category) }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $label }}
            </div>
            <div class="modal-footer">
                <a href="{{ route('serverstatus.index') }}" class="btn btn-outline-secondary">{{ trans('serverstatus::messages.banner.details') }}</a>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ trans('serverstatus::messages.banner.understood') }}</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Shown as a one-time modal instead of a persistent banner: a banner
        // competes for the same screen area as the theme's own navbar (fixed
        // or not), while a modal overlay never collides with it. Once closed,
        // the same incident is remembered as read and won't pop up again.
        //
        // Waiting for DOMContentLoaded matters here: the theme loads Bootstrap
        // with "defer", so "bootstrap" is not defined yet if this script (it
        // sits right after <body>) ran immediately instead.
        var STORAGE_KEY = 'serverstatus_dismissed_incident';
        var modalEl = document.getElementById('serverstatus-notice-modal');

        if (! modalEl || typeof bootstrap === 'undefined') {
            return;
        }

        var incidentId = modalEl.getAttribute('data-incident-id');
        var dismissedId = null;

        try {
            dismissedId = localStorage.getItem(STORAGE_KEY);
        } catch (e) {}

        if (dismissedId === incidentId) {
            return;
        }

        var modal = new bootstrap.Modal(modalEl);

        modalEl.addEventListener('hidden.bs.modal', function () {
            try {
                localStorage.setItem(STORAGE_KEY, incidentId);
            } catch (e) {}
        });

        modal.show();
    });
</script>
