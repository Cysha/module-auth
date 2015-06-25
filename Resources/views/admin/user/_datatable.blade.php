@extends(partial('auth::admin.user._layout'))

@section('user-form')
    @include(partial('admin::admin.datatable.index'), compact('tableConfig', 'options', 'columns', 'data'))
@stop
