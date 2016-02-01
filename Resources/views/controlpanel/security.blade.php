@extends(partial('auth::controlpanel._layout'), [
    'title' => 'Security Settings'
])

@section('control-form')
        <p>This panel will let you control your security options whilst on this website. Each section is handled seperately so ensure you hit the right submit button.</p>
    </div>
</div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">2 Factor Authentication</h3>
        </div>
        <div class="panel-body">
            @if (!$user->hasEnabled2fa)
                {!! Former::horizontal_open(route('pxcms.user.enable_2fa')) !!}
                    <p>2 Factor Authentication is currently disabled. If you would like to enable it, use the button below.</p>
                    <button class="btn-labeled btn btn-success" type="submit">
                        <span class="btn-label">Enable 2Factor Authentication</span>
                    </button>
                {!! Former::close() !!}
            @else
                @if (!$user->require2fa)
                <p>2 Factor Authentication requires a small bit of setting up before it can be used, follow instructions below.</p>
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header">
                            <div class="page-title"><h3>Step 1 <small>Scan the QR Code with your device.</small></h3></div>
                        </div>
                        <img src="{{ $qr2fa }}" alt="2 Factor Authentication Code">
                    </div>
                    <div class="col-md-12">
                        <div class="page-header">
                            <div class="page-title"><h3>Step 2 <small>Enter the code your device gives you to validate your 2fa is working.</small></h3></div>
                        </div>
                        {!! Former::horizontal_open(route('pxcms.user.verify_2fa')) !!}
                            {!! Former::text('verify_2fa') !!}

                            <button class="btn-labeled btn btn-success" type="submit">
                                <span class="btn-label">Verify 2Factor Authentication</span>
                            </button>
                        {!! Former::close() !!}
                    </div>
                </div>
                @else
                {!! Former::horizontal_open(route('pxcms.user.disable_2fa')) !!}
                    <p>2 Factor Authentication is Enabled. This will only be used when you login directly (not using a social media site).</p>
                    <button class="btn-labeled btn btn-success" type="submit">
                        <span class="btn-label">Disable 2Factor Authentication</span>
                    </button>
                {!! Former::close() !!}
                @endif

            @endif
        </div>
    </div>

{!! Former::horizontal_open(route('pxcms.user.update_password')) !!}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Password</h3>
        </div>
        <div class="panel-body">
            {!! Former::password('old_password')->label('old Password') !!}
            {!! Former::password('new_password')->label('New Password') !!}

            {!! Former::password('new_password_confirmation')->label('Confirm Password') !!}

            <button class="btn-labeled btn btn-success pull-right" type="submit">
                <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
            </button>
        </div>
    </div>
{!! Former::close() !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Sessions</h3>
        </div>
        <div class="panel-body">
            <div class="alert alert-info">
                <strong>Information:</strong> This panel has functionality planned, but not yet implemented.
            </div>
        </div>
    </div>
@stop
