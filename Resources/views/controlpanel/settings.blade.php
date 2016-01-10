@extends(partial('auth::controlpanel._layout'), ['title' => 'Account Settings'])

@section('control-form')
{!! Former::horizontal_open() !!}
    {!! Former::text('username') !!}

    {!! Former::text('name')->label('Full Name') !!}

    @if (config('cms.auth.config.users.force_screename', null) === null)
    {!! Former::select('use_nick')->options([
        '0' => $user->username,
        '1' => $user->name,
    ], $user->use_nick)->label('Site Screename') !!}
    @endif

    {!! Former::text('email') !!}

    <button class="btn-labeled btn btn-success pull-right" type="submit">
        <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
    </button>
{!! Former::close() !!}
@stop
