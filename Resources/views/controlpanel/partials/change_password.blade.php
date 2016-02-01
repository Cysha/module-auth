{!! Former::horizontal_open() !!}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Password</h3>
        </div>
        <div class="panel-body">
            <div class="alert alert-warning"><strong>Warning:</strong> {{ trans('auth::auth.user.expired_password') }}</div>
            {!! Former::password('old_password')->label('old Password') !!}
            {!! Former::password('new_password')->label('New Password') !!}

            {!! Former::password('new_password_confirmation')->label('Confirm Password') !!}

            <button class="btn-labeled btn btn-success pull-right" type="submit">
                <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
            </button>
        </div>
    </div>
{!! Former::close() !!}
