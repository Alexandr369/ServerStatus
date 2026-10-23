@php
    $category = $incident->categoryOrDefault();
    $color = \Azuriom\Plugin\ServerStatus\Models\StatusIncident::categoryColor($category);
    $label = $incident->title ?? trans('serverstatus::messages.incidents.category_'.$category.'_default', [
        'server' => $incident->server?->name ?? trans('serverstatus::messages.incidents.all_servers'),
    ]);
@endphp
<div id="serverstatus-sitewide-banner" class="alert alert-{{ $color }} alert-dismissible fade show rounded-0 mb-0 text-center" role="alert" style="position: fixed; top: 0; left: 0; right: 0; z-index: 10000;">
    <strong>{{ trans('serverstatus::messages.incidents.category_'.$category) }}</strong> — {{ $label }}
    <a href="{{ route('serverstatus.index') }}" class="btn btn-sm btn-dark ms-2">{{ trans('serverstatus::messages.banner.details') }}</a>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<script>
    (function () {
        // The theme's own navbar may be fixed/sticky too, so the banner is
        // forced above everything (fixed + a very high z-index) instead of
        // relying on its position in the HTML flow, and the page content is
        // pushed down by the banner's real height to avoid overlapping it.
        var banner = document.getElementById('serverstatus-sitewide-banner');

        if (! banner) {
            return;
        }

        var push = function () {
            document.body.style.marginTop = banner.offsetHeight + 'px';
        };

        push();

        window.addEventListener('resize', push);

        banner.addEventListener('closed.bs.alert', function () {
            document.body.style.marginTop = '';
            window.removeEventListener('resize', push);
        });
    })();
</script>
