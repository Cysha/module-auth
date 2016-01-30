<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">ReCaptcha <small>(<a href="https://www.google.com/recaptcha/admin" target="_blank">Install Instructions</a>)</small></h3>
    </div>
    <div class="panel-body">
        {!! Form::Config('recaptcha.public_key')->label('Site Key') !!}
        {!! Form::Config('recaptcha.private_key')->label('Secret Key') !!}
    </div>
</div>
