@extends('layouts.admin')
@include('admin.cloudservers.nav', ['activeTab' => 'games'])

@section('title')
    Cloud Servers
@endsection

@section('content-header')
<h1>Cloud Servers <small>You can create games.</small></h1>
<ol class="breadcrumb">
    <li><a href="{{ route('admin.index') }}">Admin</a></li>
    <li><a href="{{ route('admin.cloudservers.index') }}">Cloud Servers</a></li>
    <li class="active">Games</li>
</ol>
@endsection

@section('content')
	@yield('settings::nav')
	<div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Create Game</h3>
            <div class="box-tools">
                <a href="{{ route('admin.cloudservers.games') }}">
                    <button type="button" class="btn btn-sm btn-primary" style="border-radius: 0 3px 3px 0;margin-left:-1px;">Back</button>
                </a>
            </div>
        </div>
		<form method="POST" action="{{ route('admin.cloudservers.game.create') }}">
    		<div class="box-body">
        		<div class="row">
            		<div class="col-md-4">
                		<label for="name">Game Name</label>
                		<input name="name" type="text" value="" class="form-control"></input>
            		</div>
		            <div class="col-md-4">
		                <label for="img">Game Picture</label>
		                <input name="img" class="form-control"></input>
		            </div>
                    <div class="col-md-4">
                        <label for="eggid">Game Egg</label>
                        <select name="eggid" class="form-control">
                        @foreach($eggs as $egg)
                            <option value="{{ $egg->id }}">{{ $egg->name }}</option>
                        @endforeach
                        </select>
                    </div>
		        </div>
    		</div>
    		<div class="box-footer">
    			@csrf
        		<button class="btn btn-success pull-right">Create</button>
    		</div>
		</div>
	</form>
@endsection

@section('footer-scripts')
    @parent
@endsection
