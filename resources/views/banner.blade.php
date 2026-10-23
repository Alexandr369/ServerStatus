@php
    $category = $incident->categoryOrDefault();
    $color = \Azuriom\Plugin\ServerStatus\Models\StatusIncident::categoryColor($category);
    $label = $incident->title ?? trans('serverstatus::messages.incidents.category_'.$category.'_default', [
        'server' => $incident->server?->name ?? trans('serverstatus::messages.incidents.all_servers'),
    ]);
@endphp
<div class="alert alert-{{ $color }} alert-dismissible fade show rounded-0 mb-0 text-center" role="alert">
    <strong>{{ trans('serverstatus::messages.incidents.category_'.$category) }}</strong> — {{ $label }}
    <a href="{{ route('serverstatus.index') }}" class="btn btn-sm btn-dark ms-2">{{ trans('serverstatus::messages.banner.details') }}</a>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
