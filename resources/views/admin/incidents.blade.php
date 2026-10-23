@extends('admin.layouts.admin')

@section('title', trans('serverstatus::admin.nav.incidents'))

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header">{{ trans('serverstatus::admin.incidents.add_incident') }}</div>
        <div class="card-body">
            <form action="{{ route('serverstatus.admin.incidents.store') }}" method="POST" class="row g-2 align-items-end">
                @csrf

                <div class="col-md-2">
                    <label class="form-label">{{ trans('serverstatus::admin.incidents.category') }}</label>
                    <select class="form-select" name="category" required="required">
                        @foreach (\Azuriom\Plugin\ServerStatus\Models\StatusIncident::CATEGORIES as $category)
                            <option value="{{ $category }}" @selected($category === 'maintenance')>
                                {{ trans('serverstatus::messages.incidents.category_'.$category) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">{{ trans('serverstatus::admin.incidents.server') }}</label>
                    <select class="form-select" name="server_id">
                        <option value="">{{ trans('serverstatus::admin.incidents.all_servers') }}</option>
                        @foreach ($servers as $server)
                            <option value="{{ $server->id }}">{{ $server->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">{{ trans('serverstatus::admin.incidents.title_field') }}</label>
                    <input type="text" class="form-control" name="title" required="required" maxlength="255">
                </div>

                <div class="col-md-2">
                    <label class="form-label">{{ trans('serverstatus::admin.incidents.started_at') }}</label>
                    <input type="datetime-local" class="form-control" name="started_at" required="required">
                </div>

                <div class="col-md-2">
                    <label class="form-label">{{ trans('serverstatus::admin.incidents.resolved_at') }}</label>
                    <input type="datetime-local" class="form-control" name="resolved_at">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus-lg"></i> {{ trans('messages.actions.add') }}
                    </button>
                </div>
            </form>
            <div class="form-text">{{ trans('serverstatus::admin.incidents.resolved_at_help') }}</div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-wrapper table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">{{ trans('serverstatus::admin.incidents.category') }}</th>
                            <th scope="col">{{ trans('serverstatus::admin.incidents.origin') }}</th>
                            <th scope="col">{{ trans('serverstatus::admin.incidents.server') }}</th>
                            <th scope="col">{{ trans('serverstatus::admin.incidents.title_field') }}</th>
                            <th scope="col">{{ trans('serverstatus::admin.incidents.started_at') }}</th>
                            <th scope="col">{{ trans('messages.fields.status') }}</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($incidents as $incident)
                            <tr>
                                <td>
                                    <span class="badge bg-{{ \Azuriom\Plugin\ServerStatus\Models\StatusIncident::categoryColor($incident->categoryOrDefault()) }}">
                                        {{ trans('serverstatus::messages.incidents.category_'.$incident->categoryOrDefault()) }}
                                    </span>
                                </td>
                                <td>{{ trans('serverstatus::admin.incidents.type_'.$incident->type) }}</td>
                                <td>{{ $incident->server?->name ?? trans('serverstatus::admin.incidents.all_servers') }}</td>
                                <td>{{ $incident->title }}</td>
                                <td>{{ format_date($incident->started_at, true) }}</td>
                                <td>
                                    @if ($incident->isUpcoming())
                                        <span class="badge bg-info">{{ trans('serverstatus::messages.incidents.upcoming') }}</span>
                                    @elseif ($incident->isOngoing())
                                        <span class="badge bg-danger">{{ trans('serverstatus::messages.incidents.ongoing') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ trans('serverstatus::messages.incidents.resolved') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if ($incident->isOngoing())
                                        <form action="{{ route('serverstatus.admin.incidents.update', $incident) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="category" value="{{ $incident->categoryOrDefault() }}">
                                            <input type="hidden" name="title" value="{{ $incident->title }}">
                                            <input type="hidden" name="started_at" value="{{ $incident->started_at->format('Y-m-d\TH:i') }}">
                                            <input type="hidden" name="resolved_at" value="{{ now()->format('Y-m-d\TH:i') }}">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-check-lg"></i> {{ trans('serverstatus::admin.incidents.resolve_now') }}
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('serverstatus.admin.incidents.destroy', $incident) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ trans('serverstatus::admin.incidents.delete_confirm') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">{{ trans('serverstatus::admin.incidents.none') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $incidents->links() }}
            </div>
        </div>
    </div>
@endsection
