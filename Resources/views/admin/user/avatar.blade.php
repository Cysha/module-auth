@extends(partial('auth::admin.user._layout'))

@section('user-form')
{!! Former::horizontal_open() !!}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Avatar</h3>
        </div>
        <table class="panel-body table table-striped">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Avatar</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($user->getAvatarList() as $name => $avatar)
                <tr>
                    <td>{!! Former::radio('avatar')->radios([
                        $name => ['name' => 'avatar', 'value' => $avatar]
                    ])->label(false) !!}</td>
                    <td><img src="{{ $avatar }}" alt="" style="height: 80px; width: 80px;"></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <button class="btn-labeled btn btn-success pull-right" type="submit">
        <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
    </button>
{!! Former::close() !!}
@stop
