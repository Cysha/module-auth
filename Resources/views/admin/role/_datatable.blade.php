@extends(partial('auth::admin.role._layout'))

@section('role-form')
    @include(partial('admin::admin.datatable.index'), compact('tableConfig', 'options', 'columns', 'data'))
@stop
