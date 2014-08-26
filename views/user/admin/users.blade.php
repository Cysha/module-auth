<div class="row">
    <div class="col-md-2"> @include('auth::user.admin.nav') </div>
    <div class="col-md-7">
    {{ Former::horizontal_open( URL::route('admin.user.edit', $user->id) ) }}

        {{ Former::text('username')->disabled() }}

        {{ Former::text('first_name') }}

        {{ Former::text('last_name') }}

        {{ Former::text('email') }}

        {{ Former::radio('verified')->radios(array('0' => 'No', '1' => 'Yes'))->label('Verified')->inline() }}

        {{ Former::radio('disabled')->radios(array('0' => 'No', '1' => 'Yes'))->label('Disabled')->inline() }}


        <button class="btn-labeled btn btn-success pull-right" type="submit">
            <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
        </button>

    {{ Former::close() }}
    </div>
</div>
