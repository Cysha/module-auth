@extends(partial('auth::controlpanel._layout'), ['title' => 'Dashboard'])

@section('control-form')

{!! \Debug::dump($user) !!}


@stop
