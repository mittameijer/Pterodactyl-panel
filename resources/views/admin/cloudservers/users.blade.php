@extends('layouts.admin')
@include('admin.cloudservers.nav', ['activeTab' => 'users'])

@section('title')
    Cloud Servers
@endsection

@section('content-header')
<h1>Cloud Servers <small>You can manage cloud servers users.</small></h1>
<ol class="breadcrumb">
    <li><a href="{{ route('admin.index') }}">Admin</a></li>
    <li><a href="{{ route('admin.cloudservers.index') }}">Cloud Servers</a></li>
    <li class="active">Users</li>
</ol>
@endsection

@section('content')
    @yield('settings::nav')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Users</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Client Name</th>
                            <th>Username</th>
                            <th>Memory (MB)</th>
                            <th>Disk (MB)</th>
                            <th class="text-right">Action</th>
                        </tr>
                        @if(!$users->isEmpty())
                        @foreach($users as $user)
                        <tr>
                            <th><code>{{ $user->id }}</code></th>
                            <td><a href="{{ route('admin.users.view', $user->id) }}">{{ $user->email }}</a> @if($user->root_admin)<i class="fa fa-star text-yellow"></i>@endif</td>
                            <th>{{ $user->username }}</th>
                            <th>{{ $user->name_last }}, {{ $user->name_first }}</th>
                            <form method="POST" action="{{ route('admin.cloudservers.user.update', $user->id) }}">
                                <th>
                                    <div class="input-group">
                                        <input type="text" name="memory" class="form-control" value="{{ $user->memory }}"/>
                                        <span class="input-group-addon">MB</span>
                                    </div>
                                </th>
                                <th>
                                    <div class="input-group">
                                        <input type="text" name="disk" class="form-control" value="{{ $user->disk }}"/>
                                        <span class="input-group-addon">MB</span>
                                    </div>
                                </th>
                                <th class="text-right">
                                    @csrf
                                    <button class="btn btn-sm btn-success">Save</button>
                                </th>
                            </form>
                        </tr> 
                        @endforeach
                        @endif
                        @if($users->isEmpty())
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
    </div>
@endsection

@section('footer-scripts')
    @parent
@endsection
