<div class="row">
    <div class="col-md-2"> @include('auth::user.admin.nav') </div>
    <div class="col-md-10">
        <div class="page-heading">
            <h3>Change Password</h3>
        </div>
        {{ Former::horizontal_open( URL::route('admin.user.password', $user->id) ) }}

            {{ Former::password('password')->label('New Password') }}

            {{ Former::password('password_confirmation')->label('Confirm Password') }}

            <button class="btn-labeled btn btn-success pull-right" type="submit">
                <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
            </button>

        {{ Former::close() }}
    </div>
</div>
