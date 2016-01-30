@extends(partial('admin::admin.config._layout'))

@section('admin-config')
{!! Former::horizontal_open(route('admin.config.store')) !!}
    <div class="alert alert-warning"><strong>Warning:</strong> This panel is still WIP and might not work 100%, if at all.</div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">User Settings</h3>
        </div>
        <div class="panel-body">
            {!! Form::Config('cms.auth.config.users.require_activating', 'radio', 'false')
                ->radios([
                    'Yes' => ['value' => 'true'],
                    'No' => ['value' => 'false']
                ])
                ->label('Require Email Activation')
                ->inline()
                ->disable()
                ->inlineHelp('<i class="fa fa-warning"></i> Functionality not implemented')
            !!}
            {!! Form::Config('cms.auth.config.users.force_screenname', 'radio', 'NULL')
                ->radios([
                    'Disable' => ['value' => 'NULL'],
                    'Username' => ['value' => '0'],
                    'Full Name' => ['value' => '1']
                ])
                ->label('Force Users Public Name')
                ->inline()
            !!}

        </div>
    </div>

    <button class="btn-labeled btn btn-success pull-right" type="submit">
        <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
    </button>
{!! Former::close() !!}
@stop
