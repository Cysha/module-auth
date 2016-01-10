@extends(partial('auth::controlpanel._layout'), ['title' => 'Users Dashboard'])

@section('control-form')

    <div class="row">
        <div class="col-md-2"><img class="thumbnail" src="{{ array_get($user, 'avatar') }}" alt="{{ array_get($user, 'screenname') }}'s Avatar"/></div>
        <div class="col-md-10"><h1>{{ array_get($user, 'screenname') }}</h1></div>
    </div>

    <div class="alert alert-info">
        <p><strong>Information:</strong> This panel has functionality planned, but not yet implemented.</p>
    </div>
@stop
