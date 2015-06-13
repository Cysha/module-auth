@extends(partial('auth::admin.user._layout'))

@section('user-form')
    @include('admin::admin.datatable.index', compact('columns', 'filterOptions', 'options'))
@stop
