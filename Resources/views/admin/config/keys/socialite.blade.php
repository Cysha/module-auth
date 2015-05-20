<div class="alert alert-info"><strong>Information:</strong> This page will list the socialite providers that are working at the moment. Below the first table you will see another list, these are the ones that can be installed.</div>

<table class="panel-body table table-bordered table-striped">
    <thead>
        <tr>
            <th>Service</th>
            <th>Client ID</th>
            <th>Client Secret</th>
        </tr>
    </thead>
    <tbody>
    @foreach($socialiteProviders as $provider => $info)
        <?php
        if (!in_array(strtolower($provider), $installedProviders)) {
            continue;
        }
        ?>
        <tr>
            <td><i class="fa fa-fw {{ array_get($info, 'icon') }}"></i> {{ ucwords($provider) }}</td>
            <td>{!! Form::Config('services.'.$provider.'.client_id')->label(false) !!}</td>
            <td>{!! Form::Config('services.'.$provider.'.client_secret')->label(false) !!}
                {!! Form::Config('services.'.$provider.'.redirect', 'hidden', route('pxcms.user.provider', ['provider' => $provider]))->label(false) !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="alert alert-info"><strong>Information:</strong> Use the buttons below to install the Socialite Provider of your choice. After you have installed the provider, it should show up in the top table ready for configuring.</div>
<table class="panel-body table table-bordered table-striped">
    <tbody>
    <?php $counter = 0; ?>
    <tr>
    @foreach($socialiteProviders as $provider => $info)
        <?php
        if (in_array(strtolower($provider), $installedProviders)) {
            continue;
        }
        ?>
        @if ($counter % 6 == 0)
            </tr><tr>
        @endif
            <td><i class="fa fa-fw {{ array_get($info, 'icon') }}"></i> {{ ucwords($provider) }}</td>
        <?php $counter++; ?>
    @endforeach
    </tr>
    </tbody>
</table>
