@extends('layouts.admin')
@include('admin.cloudservers.nav', ['activeTab' => 'logs'])

@section('title')
    Cloud Servers
@endsection

@section('content-header')
<h1>Cloud Servers <small>You see cloud servers logs.</small></h1>
<ol class="breadcrumb">
    <li><a href="{{ route('admin.index') }}">Admin</a></li>
    <li><a href="{{ route('admin.cloudservers.index') }}">Cloud Servers</a></li>
    <li class="active">Logs</li>
</ol>
@endsection

@section('content')
    @yield('settings::nav')
<div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Logs</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>Creator</th>
                            <th>Type</th>
                            <th>Memory</th>
                            <th>Disk</th>
                            <th>Created</th>
                        </tr>
                        @if(!$logs->isEmpty())
                        @foreach($logs as $log)
                        <tr>
                            <th>{{ $log->id }}</th>
                            <th>{{ $log->creator }}</th>
                            <th>{{ $log->type }}</th>
                            <th>{{ $log->memory }}MB</th>
                            <th>{{ $log->disk }}MB</th>
                            <th>{{ $log->created_at }}</th>
                        </tr> 
                        @endforeach
                        @endif
                        @if($logs->isEmpty())
                        <tr>
                            <th></th>
                            <th></th>
                            
                            <th></th>
                            <th>No Logs Found</th> 
                            <th></th>
                        </tr> 
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>

@endsection

@section('footer-scripts')
    @parent
@endsection
