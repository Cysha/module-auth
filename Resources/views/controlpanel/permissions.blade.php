@extends(partial('auth::controlpanel._layout'))

@section('control-form')
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Permission</th>
            <th>Resource Type</th>
            <th>Can?</th>
        </tr>
    </thead>
    <tbody>
    @foreach($permissions as $permission)
        <tr>
            <td>{{ $permission->action }}</td>
            <td>{{ $permission->resource_type }}</td>
            <td>{!! Lock::can($permission->action, $permission->resource_type)
                    ? '<i class="fa fa-check-square-o"></i>'
                    : '<i class="fa fa-square-o"></i>'
                !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@stop
