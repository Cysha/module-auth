@extends(partial('admin::admin.config._layout'))

@section('admin-config')
{!! Former::horizontal_open(route('admin.config.store')) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Login Throttling</h3>
        </div>
        <div class="panel-body">
            {!! Form::Config('cms.auth.config.users.login.throttlingEnabled', 'radio', 'false')
                ->radios([
                    'No' => ['value' => 'false'],
                    'Yes' => ['value' => 'true'],
                ])
                ->label('Enable Login Throttling?')
                ->inlineHelp(trans('auth::auth.login.throttling'))
                ->inline()
            !!}

            {!! Form::Config('cms.auth.config.users.login.lockoutTime', 'number')
                ->label('Lockout Time <small>(seconds)</small>') !!}

            {!! Form::Config('cms.auth.config.users.login.maxLoginAttempts', 'number')
                ->label('Max Login Attempts') !!}

        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Password Requirements</h3>
        </div>
        <div class="panel-body">

            {!! Form::Config('cms.auth.config.users.login.force_password', 'radio', 'false')
                ->radios([
                    'No' => ['value' => 'false'],
                    'Yes' => ['value' => 'true'],
                ])
                ->label('Force more secure passwords?')
                ->inline()
                ->inlineHelp(trans('auth::auth.user.secure_password'))
            !!}

            {!! Form::Config('cms.auth.config.users.login.expire_passwords', 'radio', 'false')
                ->radios([
                    'No' => ['value' => 'false'],
                    'Yes' => ['value' => 'true'],
                ])
                ->label('Force passwords to expire?')
                ->inline()
                ->inlineHelp(trans('auth::auth.user.expire_passwords'))
            !!}

            {!! Form::Config('cms.auth.config.users.login.password_age', 'select', 'false')
                ->options([
                    '2 Weeks' => ['value' => 1209600],
                    '1 Month' => ['value' => 2419200],
                    '3 Months' => ['value' => 7257600],
                    '6 Months' => ['value' => 14515200],
                    '1 Year' => ['value' => 31536000],
                ])
                ->label('Password Age')
                ->inline()
                ->inlineHelp(trans('auth::auth.user.password_age'))
            !!}

        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Recaptcha Settings</h3>
        </div>
        <div class="panel-body">
            @if (in_array(null, [config('recaptcha.public_key', null), config('recaptcha.private_key', null)]))
            <div class="alert alert-warning"><strong>Warning:</strong> Recaptcha has not been configured correctly. It will not work until this issue has been resolved.</div>
            @endif

            {!! Form::Config('cms.auth.config.recaptcha.login_form', 'radio', 'false')
                ->radios([
                    'No' => ['value' => 'false'],
                    'Yes' => ['value' => 'true'],
                ])
                ->label('Protect Login Form?')
                ->inline()
            !!}

            {!! Form::Config('cms.auth.config.recaptcha.register_form', 'radio', 'false')
                ->radios([
                    'No' => ['value' => 'false'],
                    'Yes' => ['value' => 'true'],
                ])
                ->label('Protect Registration Form?')
                ->inline()
            !!}

        </div>
    </div>

    <button class="btn-labeled btn btn-success pull-right" type="submit">
        <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
    </button>
{!! Former::close() !!}
@stop
