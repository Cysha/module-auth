<div class="row">
    <div class="col-md-2"> @include('users::user.admin.nav') </div>
    <div class="col-md-10">
        <div class="page-heading">
            <h3>User Changelog</h3>
        </div>

        <table class="table table-hover table-bordered">
            <thead>
                <th width="25%">Username</th>
                <th width="25%">Field</th>
                <th width="25%">Old Value</th>
                <th width="25%">New Value</th>
            </thead>
            <tbody>
            @if(count($user->revisionHistory))
                <?php
                $oldHeader = null;
                $revisionHistory = $user->revisionHistory()->get()->reverse();
                ?>
                @foreach($revisionHistory->slice(0, 10, true) as $history)
                    <?php
                    $history = (object)$history;
                    $header = Carbon\Carbon::parse($history->created_at)->format('D dS M Y \<\s\m\a\l\l\>h:i:s\<\/\s\m\a\l\l\>');
                    if( $header != $oldHeader ):
                        $oldHeader = $header;
                    ?>
                    <tr class="info">
                        <td colspan="4">{{ $header }}</td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td>{{ $history->userResponsible()->username or 'System' }}</td>
                        <td>{{ $history->fieldName() }}</td>
                        <td>{{ $history->oldValue() }}</td>
                        <td>{{ $history->newValue() }}</td>
                    </tr>
                @endforeach
            @else
                <tr class="danger">
                    <td colspan="4">Error: No History Found for User.</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>