@extends(partial('auth::admin.user._layout'))

@section('user-form')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Password</h3>
            </div>
            <div class="panel-body">
                {!! Former::horizontal_open() !!}
                    {!! Former::password('password')->label('New Password') !!}

                    {!! Former::password('password_confirmation')->label('Confirm Password') !!}

                    <button class="btn-labeled btn btn-success pull-right" type="submit">
                        <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
                    </button>
                {!! Former::close() !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">2 Factor Authentication</h3>
            </div>
            <div class="panel-body">
                @if (!$user->hasEnabled2fa)
                    <p>2 Factor Authentication is <strong>disabled</strong> for this user.</p>
                @else
                    {!! Former::horizontal_open(route('admin.user.disable2fa', $user->id)) !!}
                        <p>2 Factor Authentication is <strong>enabled</strong> for this user.</p>
                        <button class="btn-labeled btn btn-success" type="submit" data-confirm="Are you sure you want to disable 2FA for this user?">
                            <span class="btn-label">Disable 2 Factor Authentication</span>
                        </button>
                    {!! Former::close() !!}
                @endif
            </div>
        </div>
    </div>

    @if (config('cms.auth.config.users.expire_passwords', false))
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Expire Current Password</h3>
            </div>
            <div class="panel-body">
                <div class="alert alert-info"><strong>Information: </strong> Using this option, will force the user to reset their password upon next login.</div>
                {!! Former::horizontal_open(route('admin.user.expirepassword', $user->id)) !!}
                    <button class="btn-labeled btn btn-success" type="submit" data-confirm="Are you sure you want to expire this users password?">
                        <span class="btn-label">Expire Users Password</span>
                    </button>
                {!! Former::close() !!}
            </div>
        </div>
    </div>
    @endif
</div>

@stop
