<div class="row">
    @if( Request::segment(3) != 'add' )
    <div class="col-md-2"> @include('users::role.admin.nav') </div>
    @endif
    <div class="col-md-7">
    @if( Request::segment(3) != 'add' )
    {{ Former::horizontal_open( URL::route('admin.role.edit', $role->id) ) }}
    @else
    {{ Former::horizontal_open( URL::route('admin.role.store') ) }}
    @endif

        {{ Former::text('name') }}

        {{ Former::textarea('description') }}

        {{ Former::framework('Nude') }}
        <div class="form-group">
            <label class="control-label col-md-3" for="color">Color</label>
            <div class="col-md-9">
                <div data-name="color" data-color="{{ $role->color }}" class="bfh-colorpicker">
                </div>
            </div>
        </div>
        {{ Former::framework('TwitterBootstrap3') }}


        <button class="btn-labeled btn btn-success pull-right" type="submit">
            <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
        </button>

    {{ Former::close() }}
    </div>
</div>