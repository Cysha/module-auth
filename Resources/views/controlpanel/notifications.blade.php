@extends(partial('auth::controlpanel._layout'), ['title' => 'Notifications'])

@section('control-form')

{!! \Debug::dump($user) !!}

@stop
