@extends(partial('auth::admin.role._layout'))

@section('role-form')
{!! Former::horizontal_open() !!}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Basic Role Info</h3>
        </div>
        <div class="panel-body">
            {!! Former::text('name') !!}
        </div>
    </div>

    <button class="btn-labeled btn btn-success pull-right" type="submit">
        <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
    </button>
{!! Former::close() !!}
@stop
