@extends(partial('auth::controlpanel._layout'), ['title' => 'Avatars'])

@section('control-form')

{!! \Debug::dump($user) !!}

@stop
