@extends(partial('auth::controlpanel._layout'), ['title' => 'Account Settings'])

@section('control-form')

{!! \Debug::dump($user) !!}

@stop
