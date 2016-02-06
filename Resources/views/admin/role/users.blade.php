@extends(partial('auth::admin.role._layout'))

@section('role-form')
    @include(partial('admin::admin.datatable.index'))
@stop

@section('role-sidebar')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">Add User to Role</div>
        </div>
        <div class="panel-body">
        {!! Former::vertical_open(route('admin.role.users.add', request()->segment(3)))->data_method('post')->data_remote('true')->data_datatable('refresh') !!}
            {!! Former::text('username') !!}

            <button class="btn-labeled btn btn-success pull-right" type="submit">
                <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
            </button>

        {!! Former::close() !!}
        </div>
    </div>
@stop
