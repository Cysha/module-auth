@extends(partial('auth::controlpanel._layout'), ['title' => 'Avatars'])

@section('control-form')
    <p>This panel will let you assign your own avatar, at the moment we only support those that have been assigned by Gravatar or a Social login.</p>
    </div>
</div>
{!! Former::horizontal_open() !!}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Avatar</h3>
        </div>
        <div class="panel-body">
            <div class="row">
            @set($counter, 0)
            @foreach ($user->getAvatarList() as $name => $avatar)
                @if($counter%4 === 0)
                </div><div class="row">
                @endif
                <div class="col-md-3">
                    {!! Former::radio('avatar')->radios([
                        $name => ['id' => $name, 'value' => $avatar]
                    ])->label(false) !!}
                    <label for="{{ $name }}">
                        <img src="{{ $avatar }}" alt="{{ $user->screenname }}'s {{ $name }} Avatar" class="thumbnail" style="height: 80px; width: 80px;">
                    </label>
                </div>

                @set($counter, $counter+1)
            @endforeach
            </div>

            <button class="btn-labeled btn btn-success pull-right" type="submit">
                <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
            </button>
        </div>
    </div>
{!! Former::close() !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">Upload Your Own</div>
        </div>
        <div class="panel-body">
            <div class="alert alert-info">
                <strong>Information:</strong> Uploading an avatar will immediately set it as your active avatar.
            </div>

            {!! Former::open()->route('pxcms.user.avatar.upload')->class('dropzone') !!}
                <div class="fallback">
                    <input name="file" type="file" multiple />
                </div>
            {!! Former::close() !!}
        </div>
    </div>
@stop
