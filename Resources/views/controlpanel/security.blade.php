@extends(partial('auth::controlpanel._layout'), [
    'title' => 'Security Settings'
])

@section('control-form')
        <p>This panel will let you control your security options whilst on this website.</p>
    </div>
</div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">2 Factor Authentication</h3>
        </div>
        <div class="panel-body">
            <div class="alert alert-info">
                <strong>Information:</strong> This panel has functionality planned, but not yet implemented.
            </div>
        </div>
    </div>

{!! Former::horizontal_open(route('pxcms.user.update_password')) !!}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Password</h3>
        </div>
        <div class="panel-body">
            {!! Former::password('old_password')->label('old Password') !!}
            {!! Former::password('new_password')->label('New Password') !!}

            {!! Former::password('new_password_confirmation')->label('Confirm Password') !!}

            <button class="btn-labeled btn btn-success pull-right" type="submit">
                <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
            </button>
        </div>
    </div>
{!! Former::close() !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Sessions</h3>
        </div>
        <div class="panel-body">
            <div class="alert alert-info">
                <strong>Information:</strong> This panel has functionality planned, but not yet implemented.
            </div>
        </div>
    </div>
@stop
