@extends('layouts.app')

@section('title', trans('serverstatus::messages.incidents.title'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">{{ trans('serverstatus::messages.incidents.title') }}</h1>
                <a href="{{ route('serverstatus.index') }}" class="small">{{ trans('serverstatus::messages.incidents.back_to_status') }}</a>
            </div>

            <div class="card shadow mb-4">
                <ul class="list-group list-group-flush">
                    @forelse ($incidents as $incident)
                        @include('serverstatus::partials.incident', ['incident' => $incident])
                    @empty
                        <li class="list-group-item">{{ trans('serverstatus::messages.incidents.none') }}</li>
                    @endforelse
                </ul>
            </div>

            {{ $incidents->links() }}
        </div>
    </div>
@endsection
