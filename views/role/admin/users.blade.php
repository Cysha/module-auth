<div class="row">
    <div class="col-md-2"> @include('auth::role.admin.nav') </div>
    <div class="col-md-7">
    {{ Former::horizontal_open( URL::route('admin.role.user', $role->id) ) }}


    {{ Former::close() }}
    </div>
</div>
