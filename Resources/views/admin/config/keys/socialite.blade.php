<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Socialite</h3>
    </div>
    <table class="panel-body table table-striped">
        <thead>
            <tr>
                <th>Service</th>
                <th>Client ID</th>
                <th>Client Secret</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Todo: move this to a view composer / service
        $socialiteProviders = ['facebook', 'twitter', 'google', 'github'];
        $path = base_path('vendor/socialiteproviders/');

        if (File::exists($path)) {
            foreach (File::Directories($path) as $dir) {
                $dir = class_basename($dir);
                if ($dir == 'manager') { continue; }

                $socialiteProviders[] = $dir;
            }
        }

        ?>
        @foreach($socialiteProviders as $provider)
            <tr>
                <td>{{ ucwords($provider) }}</td>
                <td>{!! Form::Config('services.'.$provider.'.client_id')->label(false) !!}</td>
                <td>{!! Form::Config('services.'.$provider.'.client_secret')->label(false) !!}
                    {!! Form::Config('services.'.$provider.'.redirect', 'hidden', route('pxcms.user.provider', ['provider' => $provider]))->label(false) !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
