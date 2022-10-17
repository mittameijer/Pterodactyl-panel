@extends('layouts.admin')
@include('admin.cloudservers.nav', ['activeTab' => 'games'])

@section('title')
    Cloud Servers
@endsection

@section('content-header')
<h1>Cloud Servers <small>You can manage games.</small></h1>
<ol class="breadcrumb">
    <li><a href="{{ route('admin.index') }}">Admin</a></li>
    <li><a href="{{ route('admin.cloudservers.index') }}">Cloud Servers</a></li>
    <li class="active">Games</li>
</ol>
@endsection

@section('content')
    @yield('settings::nav')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Games</h3>
                    <div class="box-tools">
                        <a href="{{ route('admin.cloudservers.game.new') }}">
                            <button type="button" class="btn btn-sm btn-primary" style="border-radius: 0 3px 3px 0;margin-left:-1px;">Create New</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @foreach($eggs as $egg)
        <div class="col-md-3">
            <div class="box">
                <form method="POST" action="{{ route('admin.cloudservers.game.status') }}">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $egg->name }}</h3>
                    </div>
                    <div class="box-body text-center">
                        <img src="{{ $egg->img }}" style="width:250px;height:150px;" alt="-" />
                    </div>
                    <div class="box-footer">
                        <a href="{{ route('admin.cloudservers.game.edit', $egg->id) }}" class="btn btn-primary">Edit</a>
                        @if($egg->status == 0)
                            @csrf
                            <button name="status" value="{{ $egg->name }}" class="btn btn-success pull-right">Enable</button>
                        @else
                            @csrf
                            <button name="status" value="{{ $egg->name }}" class="btn btn-danger pull-right">Disable</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
@endsection

@section('footer-scripts')
    @parent
@endsection
