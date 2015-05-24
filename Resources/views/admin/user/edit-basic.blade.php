@extends(partial('auth::admin.user._layout'))

@section('user-form')
{!! Former::horizontal_open() !!}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Basic User Info</h3>
        </div>
        <div class="panel-body">
            {!! Former::text('username') !!}

            {!! Former::text('first_name') !!}

            {!! Former::text('last_name') !!}

            {!! Former::text('email') !!}
        </div>
    </div>

    <button class="btn-labeled btn btn-success pull-right" type="submit">
        <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
    </button>
{!! Former::close() !!}
@stop
