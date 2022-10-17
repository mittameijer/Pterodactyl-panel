@extends('layouts.admin')
@include('admin.cloudservers.nav', ['activeTab' => 'general'])

@section('title')
    Cloud Servers
@endsection

@section('content-header')
<h1>Cloud Servers <small>You can manage cloud servers.</small></h1>
<ol class="breadcrumb">
    <li><a href="{{ route('admin.index') }}">Admin</a></li>
    <li class="active">Cloud Servers</li>
</ol>
@endsection

@section('content')
    @yield('settings::nav')
    <div class="row">
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Settings</h3>
                </div>
                <form method="POST" action="{{ route('admin.cloudservers.update') }}">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group col-12 col-md-4">
                                    <label for="cpu">Default CPU <small>(CPU %)</small></label>
                                    <input name="cpu" type="number" value="{{ $settings->default_cpu }}" class="form-control"></input>
                                </div>
                                <div class="form-group col-12 col-md-4">
                                    <label for="swap">Default Swap <small>(MB)</small></label>
                                    <input name="swap" type="number" value="{{ $settings->default_swap }}" class="form-control"></input>
                                </div>
                                <div class="form-group col-12 col-md-4">
                                    <label for="io">Default IO Block</label>
                                    <input name="io" type="number" value="{{ $settings->default_io }}" class="form-control"></input>
                                </div>

                                <div class="form-group col-12 col-md-4">
                                    <label for="database">Default Database Amount</label>
                                    <input name="database" type="number"  value="{{ $settings->default_database }}" class="form-control"></input>
                                </div>
                                <div class="form-group col-12 col-md-4">
                                    <label for="allocation">Default Allocation Amount</label>
                                    <input name="allocation" type="number" value="{{ $settings->default_allocation }}" class="form-control"></input>
                                </div>
                                <div class="form-group col-12 col-md-4">
                                    <label for="backup">Default Backup Amount</label>
                                    <input name="backup" type="number" value="{{ $settings->default_backup }}" class="form-control"></input>
                                </div>

                                <div class="form-group col-12 col-md-3">
                                    <label for="min_memory">Minimum Memory <small>(MB)</small></label>
                                    <input name="min_memory" type="number" value="{{ $settings->min_memory }}" class="form-control"></input>
                                </div>
                                <div class="form-group col-12 col-md-3">
                                    <label for="max_memory">Maximum Memory <small>(MB)</small></label>
                                    <input name="max_memory" type="number" value="{{ $settings->max_memory }}" class="form-control"></input>
                                </div>
                                <div class="form-group col-12 col-md-3">
                                    <label for="min_disk">Minimum Disk <small>(MB)</small></label>
                                    <input name="min_disk" type="number" value="{{ $settings->min_disk }}" class="form-control"></input>
                                </div>
                                <div class="form-group col-12 col-md-3">
                                    <label for="max_disk">Maximum Disk <small>(MB)</small></label>
                                    <input name="max_disk" type="number" value="{{ $settings->max_disk }}" class="form-control"></input>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        @csrf
                        <button class="btn btn-success pull-right">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Stats</h3>
                </div>
                <div class="box-body">
                    <span class="btn btn-primary">Total Servers Created: {{ $settings->totalservers }}</span>
                    <span class="btn btn-primary">Total Ram From Users: {{ $settings->totalram }}MB</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent
@endsection
