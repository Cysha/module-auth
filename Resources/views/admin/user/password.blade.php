@extends(partial('auth::admin.user._layout'))

@section('user-form')
{!! Former::horizontal_open() !!}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Password</h3>
        </div>
        <div class="panel-body">
            {!! Former::password('password')->label('New Password') !!}

            {!! Former::password('password_confirmation')->label('Confirm Password') !!}
        </div>
    </div>

    <button class="btn-labeled btn btn-success pull-right" type="submit">
        <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
    </button>
{!! Former::close() !!}
@stop
