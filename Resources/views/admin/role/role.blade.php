@extends(partial('auth::admin.user._layout'))

@section('user-form')
{!! Former::horizontal_open() !!}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Roles</h3>
        </div>
        <div class="panel-body">
            {!! Form::DBSelect('roles[]', $roles)
                ->id('roles')
                ->multiple('true')
                ->select($selected)
                ->label(false) !!}
        </div>
    </div>

    <button class="btn-labeled btn btn-success pull-right" type="submit">
        <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
    </button>
{!! Former::close() !!}
@stop
