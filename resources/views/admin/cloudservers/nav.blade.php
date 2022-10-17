@section('settings::nav')
    <div class="row">
        <div class="col-xs-12">
            <div class="nav-tabs-custom nav-tabs-floating">
                <ul class="nav nav-tabs">
                    <li @if($activeTab === 'general')class="active"@endif><a href="{{ route('admin.cloudservers.index') }}">General</a></li>
                    <li @if($activeTab === 'users')class="active"@endif><a href="{{ route('admin.cloudservers.users') }}">Users</a></li>
                    <li @if($activeTab === 'games')class="active"@endif><a href="{{ route('admin.cloudservers.games') }}">Game's</a></li>
                    <li @if($activeTab === 'logs')class="active"@endif><a href="{{ route('admin.cloudservers.logs') }}">Logs</a></li>
                </ul>
            </div>
        </div>
    </div>
@endsection