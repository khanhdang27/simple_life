@extends("Base::layouts.master")

@section("content")
    <div id="service-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('get.service.list') }}">{{ trans("Service") }}</a>
                    </li>
                    <li class="breadcrumb-item active"><a href="#">{{ trans("Create Service") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Create Service") }}</h3></div>
            <div>
                <a href="{{ url()->previous() }}" class="btn btn-info">{{ trans('Go Back') }}</a>
            </div>
        </div>
    </div>
    <div id="service" class="card">
        <div class="card-body">
            @include('Service::service._form')
        </div>
    </div>
@endsection
