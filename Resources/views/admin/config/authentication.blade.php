@extends(partial('admin::admin.config._layout'))

@section('admin-config')
{!! Former::horizontal_open(route('admin.config.store')) !!}
    @if (in_array(null, [config('recaptcha.public_key', null), config('recaptcha.private_key', null)]))
    <div class="alert alert-warning"><strong>Warning:</strong> Recaptcha has not been configured correctly. <a href="{{ route('admin.config.services') }}">Click here</a> to configure it.</div>
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Recaptcha Settings</h3>
        </div>
        <div class="panel-body">
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
                ->inline()
            !!}

            {!! Form::Config('cms.auth.config.users.login.lockoutTime', 'number')
                ->label('Lockout Time') !!}

            {!! Form::Config('cms.auth.config.users.login.maxLoginAttempts', 'number')
                ->label('Max Login Attempts') !!}

        </div>
    </div>

    <button class="btn-labeled btn btn-success pull-right" type="submit">
        <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
    </button>
{!! Former::close() !!}
@stop
