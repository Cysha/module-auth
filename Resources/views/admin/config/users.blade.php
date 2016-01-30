@extends(partial('admin::admin.config._layout'))

@section('admin-config')
{!! Former::horizontal_open(route('admin.config.store')) !!}
    <div class="alert alert-warning"><strong>Warning:</strong> This panel is still WIP and might not work 100%, if at all.</div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">User Settings</h3>
        </div>
        <div class="panel-body">

        </div>
    </div>

    <button class="btn-labeled btn btn-success pull-right" type="submit">
        <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
    </button>
{!! Former::close() !!}
@stop
